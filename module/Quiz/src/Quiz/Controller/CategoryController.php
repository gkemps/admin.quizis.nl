<?php
namespace Quiz\Controller;

use Quiz\Service\Category as CategoryService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CategoryController extends AbstractActionController
{
    /** @var CategoryService  */
    protected $categoryService;

    /**
     * @param CategoryService $categoryService
     */
    public function __construct(
        CategoryService $categoryService
    ) {
        $this->categoryService = $categoryService;
    }

    public function indexAction()
    {
        $categories = $this->categoryService->getAllCategories();

        $view = new ViewModel(
            [
                'categories' => $categories
            ]
        );

        return $view;
    }
}
