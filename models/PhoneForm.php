<?php

namespace app\models;

use app\modules\basket\models\Order;
use Yii;
use yii\base\Model;

/**
 * PhoneForm is the model behind the login form.
 *
 * @property-read User|null $user
 * @property string $username
 * @property string $phone
 * @property string $verificationCode
 * @property bool $rememberMe
 * @property bool $withCode
 *
 */
class PhoneForm extends Model
{
    public $username;
    public $phone;
    public $verificationCode;
    public $rememberMe = true;
    public $withCode = false;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            ['phone', 'required', 'message' => 'Пожалуйста, введите номер телефона'],
            [['username'], 'string'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // verificationCode is validated by validateCode()
            ['verificationCode', 'validateVerificationCode'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username'         => 'Как к Вам обращаться',
            'phone'            => 'Телефон',
            'verificationCode' => 'Код подтверждения',
            'rememberMe'       => 'Запомнить меня',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateVerificationCode($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validateVerificationCode($this->verificationCode)) {
                $this->addError($attribute, 'Неверный код.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $login =  Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);

            if ($login) {
                /** @var Order $order */
                $order = \Yii::$app->basket->getOrder();

                if (!$order->getItems()->count()) {
                    $order->delete();

                    return true;
                }

                $order->user_id = $order->created_user = $order->updated_user = \Yii::$app->user->id;
                $order->session_id = \Yii::$app->session->id;
                $order->save();

                $otherOrders =  Order::find()
                    ->findByUserId()
                    ->active()
                    ->andWhere(['!=', 'id', $order->id])
                    ->all();

                /** @var Order $otherOrder */
                foreach ($otherOrders as $otherOrder) {
                    if (!$otherOrder->getItems()->count()) {
                        $otherOrder->delete();

                        continue;
                    }

                    $otherOrder->status = Order::STATUS_DELETED;
                    $otherOrder->save();
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        $this->phone = str_replace('+7', '', $this->phone);
        $this->phone = preg_replace('/\D/', '', $this->phone);

        if ($this->_user === false) {
            $this->_user = User::findByPhone($this->phone);
        }

        return $this->_user;
    }
}
