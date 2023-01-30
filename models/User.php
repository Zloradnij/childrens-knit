<?php

namespace app\models;

use app\modules\alarm\models\SMSRU;
use app\modules\control\models\CallHistory;
use developeruz\db_rbac\interfaces\UserRbacInterface;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\conditions\BetweenColumnsCondition;
use yii\db\Expression;
use yii\httpclient\Client;
use yii\httpclient\Exception;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $phone
 * @property string $verification_code
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface, UserRbacInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByPhone(string $phone)
    {
        return static::findOne(['phone' => $phone, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findById($id)
    {
        return static::findOne(['id' => $id]);
    }
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }
    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        $ident = Yii::$app->security->validatePassword($password, $this->password_hash);
//        if($ident){
//            (new LogsVisit())->setVisit($this->id,$_SERVER['HTTP_HOST']);
//        }

        return $ident;//Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function validateVerificationCode(string $code)
    {
        $ident = static::find()
            ->where([
                'id'                => $this->id,
                'verification_code' => $code,
                'status'            => self::STATUS_ACTIVE,
            ])
            ->andWhere(
                new BetweenColumnsCondition(
                    new Expression('unix_timestamp()'),
                    'BETWEEN',
                    'updated_at',
                    new Expression('updated_at + 300')
                )
            )->one();

        return !empty($ident);
    }

    public function checkVerificationCode()
    {
        $ident = static::find()
            ->where([
                'id'                => $this->id,
                'status'            => self::STATUS_ACTIVE,
            ])
            ->andWhere(['not', ['verification_code' => null]])
            ->andWhere(
                new BetweenColumnsCondition(
                    new Expression('unix_timestamp()'),
                    'BETWEEN',
                    'updated_at',
                    new Expression('updated_at + 300')
                )
            )->one();

        return !empty($ident);
    }

    public function setVerificationCode()
    {
        $this->verification_code = rand(1000, 9999);


        $to = "+7{$this->phone}";
        $ip = $_SERVER['REMOTE_ADDR'];

        $url = "https://api.bytehand.com/v2/sms/messages";
        $data = [
            "sender"   => "SMS-INFO",
            "receiver" => $to,
            "text"     => "childrens-knit.ru Код для входа - {$this->verification_code}",
        ];
        $data = json_encode($data);

        $headers = [
            'Content-Type' => 'application/json;charset=UTF-8',
            'X-Service-Key' => '6RMi3B8fbtGWYFBBnXXCjBDNe2X4SCAj',
        ];
        $client = new Client(['baseUrl' => $url]);
        $response = $client->post($url, $data, $headers)->send();

        if (!$response->isOk) {
            throw new Exception('Ошибка отправки запроса');
        }

        $callHistory = new CallHistory();
        $callHistory->phone = $this->phone;
        $callHistory->response = $response->content;

        $response = json_decode($response->content);

        if ($response->result !== 'created') {
            throw new Exception("Ошибка отправки кода - {$response->status_text}");
        }

        $this->save();
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getUserName()
    {
        return $this->username;
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $text = "Зврегистрировался новый пользователь - {$this->phone}";

            Yii::$app->mailer->compose()
                ->setTo('zloradnij.teacher@gmail.com')
                ->setSubject('childrens-knit')
                ->setTextBody($text)
                ->send();
        }

        $this->phone = str_replace('+7', '', $this->phone);
        $this->phone = preg_replace('/\D/', '', $this->phone);

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
