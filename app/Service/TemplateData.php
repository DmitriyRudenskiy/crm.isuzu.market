<?php

namespace App\Service;


use App\Entities\Forms;
use App\Entities\Video;

class TemplateData
{
    /**
     * Шапка сайта
     * @var
     */
    private $header;

    /**
     * @var
     */
    private $benefits;

    /**
     * @var
     */
    private $products;

    /**
     * @var
     */
    private $reviews;

    /**
     * @var array
     */
    private $menu;

    /**
     * @var
     */
    private $title;

    /**
     * @var Forms[]
     */
    private $form = [];

    /**
     * @var Video
     */
    private $video;

    /**
     * @var
     */
    private $image;

    public function __construct()
    {
        $this->benefits = ['list' => []];
        $this->products = ['list' => []];
        $this->reviews = ['list' => []];
        $this->menu = [
            'logo' => [],
            'phone' => [],
            'list'=> []
        ];

        $this->form = [
            'modal' => [],
            'widget' => []
        ];

        $this->title = [];
        $this->video = [];
        $this->image = [];
    }

    public function setHeader($header)
    {
        $this->header = $header;
    }

    public function setBenefits(array $list, $title = '')
    {
        $this->benefits = [
            'title' => $title,
            'list' => $list
        ];
    }

    public function setProducts(array $list, array $label = [], $title = '', $button = [])
    {
        $this->products = [
            'title' => $title,
            'label' => $label,
            'list' => $list,
            'button' => $button
        ];
    }

    public function setReviews($list, $title = '')
    {
        $this->reviews = [
            'title' => $title,
            'list' => $list
        ];
    }

    /**
     * @return mixed
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return mixed
     */
    public function getBenefits()
    {
        return $this->benefits;
    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return mixed
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @return array
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param $logo
     * @param $items
     * @param $phone
     * @param string $logoUrl
     */
    public function setMenu($logo, $items, $phone,  $logoUrl)
    {
        $this->menu = [
            'logo' => $logo,
            'phone' => $phone,
            'list'=> $items,
            'logo_url' =>  $logoUrl
        ];
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return Forms[]
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param Forms|null $modal
     * @param Forms|null $widget
     */
    public function setForm($modal, $widget)
    {
        $this->form = [
            'modal' => $modal,
            'widget' => $widget
        ];
    }

    /**
     * @return Video
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param Video $video
     */
    public function setVideo($video)
    {
        $this->video = $video;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
}
