<?php

namespace App\Service;

use App\Repositories\BenefitsRepository;
use App\Repositories\FormsRepository;
use App\Repositories\HeadersRepository;
use App\Repositories\LabelRepository;
use App\Repositories\MenuRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\ReviewsRepository;
use App\Repositories\TitlesRepository;
use App\Repositories\VideoRepository;

class TemplateBuilder
{
    /**
     * @var BenefitsRepository
     */
    private $benefitsRepository;

    /**
     * @var HeadersRepository
     */
    private $headerRepository;

    /**
     * @var ProductsRepository
     */
    private $productRepository;

    /**
     * @var LabelRepository
     */
    private $labelRepository;

    /**
     * @var ReviewsRepository
     */
    private $reviewsRepository;

    /**
     * @var MenuRepository
     */
    private $menuRepository;

    /**
     * @var TitlesRepository
     */
    private $titleRepository;

    /**
     * @var FormsRepository
     */
    private $formRepository;

    /**
     * @var VideoRepository
     */
    private $videoRepository;

    /**
     * @var TemplateData
     */
    private $result;


    public function __construct(BenefitsRepository $benefitsRepository,
                                HeadersRepository $headerRepository,
                                ProductsRepository $productRepository,
                                LabelRepository $labelRepository,
                                MenuRepository $menuRepository,
                                TitlesRepository $titleRepository,
                                ReviewsRepository $reviewsRepository,
                                FormsRepository $formRepository,
                                VideoRepository $videoRepository)
    {
        $this->benefitsRepository = $benefitsRepository;
        $this->headerRepository = $headerRepository;
        $this->productRepository = $productRepository;
        $this->labelRepository = $labelRepository;
        $this->menuRepository = $menuRepository;
        $this->titleRepository = $titleRepository;
        $this->reviewsRepository = $reviewsRepository;
        $this->formRepository = $formRepository;
        $this->videoRepository = $videoRepository;

        $this->result = new TemplateData();
    }

    /**
     * @return $this
     */
    public function init()
    {
        $titleList = $this->titleRepository->getList();
        $this->result->setTitle($titleList);

        // Устанавливаем шапку
        $this->result->setHeader($this->headerRepository->get());

        // Устанавливаем преимущества
        $benefits = [];
        $list = $this->benefitsRepository->getList();

        foreach ($list as $value) {
            $benefits[] = [
                'cover' => $value->getPath(),
                'title' => $value->title,
                'description' => $value->description,
            ];
        }

        $this->result->setBenefits($benefits);

        $productTitle = '';

        if (!empty($titleList['title_product'])) {
            $productTitle = $titleList['title_product'];
        }

        // Устанавливаем товары
        $this->result->setProducts(
            $this->productRepository->getList(),
            $this->labelRepository->getList(),
            $productTitle,
            $this->labelRepository->getButton()
        );

        $reviewsTitle = 'Отзывы посетителей';

        if (!empty($titleList['title_reviews'])) {
            $reviewsTitle = $titleList['title_reviews'];
        }

        // Устанавливаем отзывы
        $this->result->setReviews(
            $this->reviewsRepository->getList(),
            $reviewsTitle
        );

        $logoUrl = '/';

        // адрес для логотипа в меню
        if (!empty($titleList['logo_url'])) {
            $logoUrl = $titleList['logo_url'];
        }

        // Устанавливаем меню
        $this->result->setMenu(
            $this->menuRepository->getLogo(),
            $this->menuRepository->getItems(),
            $this->menuRepository->getPhone(),
            $logoUrl
        );

        // Устанавливаем форму
        $this->result->setForm(
            $this->formRepository->getModalForm(),
            $this->formRepository->getForm()
        );

        // Устанавливаем видео
        $this->result->setVideo($this->videoRepository->get());

        return $this;
    }

    /**
     * @return TemplateData
     *
     */
    public function get()
    {
        return $this->result;
    }
}
