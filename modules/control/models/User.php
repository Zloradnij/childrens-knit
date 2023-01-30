<?php

namespace app\modules\control\models;

use app\models\Portfolio;
use app\modules\studentPortfolio\models\Profile;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Portfolio[] $portfolios
 */
class User extends \yii\db\ActiveRecord
{
    protected $profileRelations = 'profiles';

    public $password;
    public $role;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['password','username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['role'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user-short', 'ID'),
            'username' => Yii::t('user-short', 'Username'),
            'auth_key' => Yii::t('user-short', 'Auth Key'),
            'password_hash' => Yii::t('user-short', 'Password Hash'),
            'password_reset_token' => Yii::t('user-short', 'Password Reset Token'),
            'email' => Yii::t('user-short', 'Email'),
            'status' => Yii::t('user-short', 'Status'),
            'created_at' => Yii::t('user-short', 'Created At'),
            'updated_at' => Yii::t('user-short', 'Updated At'),
            'fullName' => Yii::t('user-short', 'Full Name'),
            'myClass' => Yii::t('user-short', 'My Class'),
            'password' => Yii::t('user-short','Password'),
        ];
    }

    public function getUserClass(){
        $profile = $this->profiles;

        if($profile){
            if(isset($profile['myclass']) && !empty($profile['myclass'])){
                return $profile['myclass'];
            }
        }
        return false;
    }

    public function getProfiles()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    public function getFullName(){
        $profile = $this->{$this->profileRelations};
        $fullName = '';

        if($profile){
            if(isset($profile['second_name']) && !empty($profile['second_name'])){
                $fullName = $profile['second_name'];
            }
            if(isset($profile['name']) && !empty($profile['name'])){
                $fullName .= ' ' . $profile['name'];
            }
            if(isset($profile['patronymic']) && !empty($profile['patronymic'])){
                $fullName .= ' ' . $profile['patronymic'];
            }
        }
        return $fullName;
    }
}
