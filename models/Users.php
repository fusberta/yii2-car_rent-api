<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $role
 * @property string|null $token
 *
 * @property Booking[] $booking
 * @property Reviews[] $reviews
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email'], 'string', 'max' => 50],
            ['phone', 'string', 'max' => 12],
            ['address', 'string', 'max' => 255],
            ['email', 'email'],
            ['email', 'unique'],
            ['password', 'string', 'length' => [6, 250]],
            ['phone', 'safe'],
            [['first_name', 'last_name'], 'safe'],
            ['address', 'safe'],
            ['phone', 'safe'],
            [
                ['first_name', 'last_name'],
                'match',
                'pattern' => '/^[a-zA-Zа-яА-Я]+$/u',
                'message' => 'Поле может содержать только кирилицу или латинские буквы',
               
            ],
            [
                'phone',
                'match',
                'pattern' => '/^(8|\+7)[0-9]{10}$/',
                'message' => 'Номер телефона должен начинаться с +7 или 8, и содержать 10 цифр.',
                
            ],
            [
                'password',
                'match',
                'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'message' => 'Пароль должен содержать хотя бы одну маленькую и одну прописную латинскую букву, и состоять из не менее 6 символов'
            ],
            [['email', 'password'], 'required', 'on' => 'register'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'password' => 'Password',
            'phone' => 'Phone',
            'address' => 'Address',
            'role' => 'Role',
            'token' => 'Token',
        ];
    }

    public function register()
    {
        $user = new Users();
        $user->setScenario('register');
        if ($this->validate()) {
            $user->first_name = $this->first_name;
            $user->last_name = $this->last_name;
            $user->email = $this->email;
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            $user->phone = $this->phone;
            $user->address = $this->address;

            // Генерация токена после успешной регистрации
            $token = $this->generateToken();
            $user->token = $token;

            if ($user->save()) {
                return $token;
            }
        }

        return null;
    }

    public function login()
    {
        if ($this->validate()) {
            $user = Users::findOne(['email' => $this->email]);

            $token = $this->generateToken();
            $user->token = $token;
            $user->save();

            if ($user && Yii::$app->getSecurity()->validatePassword($this->password, $user->password)) {
                return $token;
            }
        }

        return null;
    }

    public function validateAndUpdate($user, $data) {
        if ($user->validate()) {
            $this->updateUserAttributes($user, $data);
            $user->save();
            return "success". $user->user_id;
        } else { return "error"; }
    }

   

    protected function generateToken()
    {
        return Yii::$app->security->generateRandomString();
    }

    /**
     * Gets query for [[Booking]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasMany(Booking::className(), ['user_id' => 'user_id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Reviews::className(), ['user_id' => 'user_id']);
    }
}
