<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "cars".
 *
 * @property int $car_id
 * @property string|null $brand
 * @property string|null $model
 * @property int|null $year
 * @property string|null $reg_number
 * @property int|null $category_id
 * @property int $daily_price
 * @property string|null $status
 * @property string|null $image
 *
 * @property Booking[] $booking
 * @property Categories $category
 * @property Reviews[] $reviews
 */
class Cars extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cars';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['year', 'category_id', 'daily_price'], 'integer'],
            [['daily_price'], 'required'],
            [['brand', 'model'], 'string', 'max' => 100],
            [['reg_number'], 'string', 'max' => 25],
            [['status'], 'string', 'max' => 20],
            [
                ['image'],
                'file',
                'extensions' => ['png', 'jpg', 'gif'],
                'maxSize' => 2 * 1024 * 1024,
                'skipOnEmpty' => false
            ],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category_id' => 'category_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'car_id' => 'Car ID',
            'brand' => 'Brand',
            'model' => 'Model',
            'year' => 'Year',
            'reg_number' => 'Reg Number',
            'category_id' => 'Category ID',
            'daily_price' => 'Daily Price',
            'status' => 'Status',
            'image' => 'Image',
        ];
    }

    public function addCar()
    {
        if ($this->validate()) {
            $car = new Cars();
            $car->brand = $this->brand;
            $car->model = $this->model;
            $car->year = $this->year;
            $car->reg_number = $this->reg_number;
            $car->category_id = $this->category_id;
            $car->daily_price = $this->daily_price;
            $car->status = $this->status;
            $car->image = $this->image;

            if ($car->save()) {
                return $car;
            }
        }

        return false;
    }

    /**
     * Gets query for [[Booking]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasMany(Booking::className(), ['car_id' => 'car_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['category_id' => 'category_id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Reviews::className(), ['car_id' => 'car_id']);
    }

    public function beforeValidate()
    {
        $this->image = UploadedFile::getInstanceByName('image');
        return parent::beforeValidate();
    }
}
