<?php

namespace common\models;

use Yii;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%slider}}".
 *
 * @property int $id
 * @property string|null $caption Tiêu đề
 * @property string|null $tom_tat Tóm tắt
 * @property string|null $link Đường link
 *
 * @property AnhSlider[] $anhSliders
 */
class Slider extends \yii\db\ActiveRecord
{
    public $hinh_anhs;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%slider}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['caption', 'link'], 'string', 'max' => 100],
            [['tom_tat'], 'string', 'max' => 300],
            [['hinh_anhs'],'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'caption' => 'Tiêu đề',
            'tom_tat' => 'Tóm tắt',
            'link' => 'Đường link',
            'hinh_anhs' => 'Hình ảnh'
        ];
    }

    /**
     * Gets query for [[AnhSliders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnhSliders()
    {
        return $this->hasMany(AnhSlider::className(), ['slider_id' => 'id']);
    }

    public function beforeDelete()
    {
        //beforeDelete  ->  Delete   -> afterDelete
        //AnhSlider::deleteAll(['slider_id'=>$this->id]);chỉ xóa trong db ko có gọi hay liên quan đến quy trình trên
        $anh_sliders = AnhSlider::findAll(['slider_id'=>$this->id]);
        foreach ($anh_sliders as $anh_slider){
            $anh_slider->delete();//gọi hàm delete của đối tượng ảnh slider -> gọi sang hàm beforeDelete và afterDelete
        }
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        $files = UploadedFile::getInstances($this, 'hinh_anhs');
        foreach ($files as $file){
            $ten_file = time().$file->name;//lấy tên file

            $anh_slider = new AnhSlider();
            $anh_slider->slider_id = $this->id;
            $anh_slider->file = $ten_file;

            if($anh_slider->save()){
                $path = dirname(dirname(__DIR__)).'/images/'.$ten_file;
                $file->saveAs($path);
            }

        }
        //VarDumper::dump($files,10,true);
        //exit;
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }
}
