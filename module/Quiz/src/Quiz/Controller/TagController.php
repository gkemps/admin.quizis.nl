<?php
namespace Quiz\Controller;

use Quiz\Service\Tag as TagService;
use Quiz\Entity\Tag as TagEntity;
use Quiz\Form\Tag as TagForm;
use Zend\Form\FormInterface;
use Zend\View\Model\ViewModel;

class TagController extends AbstractCrudController
{
    /** @var TagService  */
    protected $tagService;

    /** @var TagForm  */
    protected $tagForm;

    /**
     * @param TagService $tagService
     * @param TagForm $tagFrom
     */
    public function __construct(
        TagService $tagService,
        Tagform $tagFrom
    ) {
        $this->tagService = $tagService;
        $this->tagForm = $tagFrom;
    }

    public function indexAction()
    {
        $tags = $this->tagService->getAllTags();

        return new ViewModel(
            [
                'tags' => $tags
            ]
        );
    }

    /**
     * @param FormInterface $form
     * @return mixed
     */
    protected function processFormData(FormInterface $form)
    {
        /** @var \Quiz\Entity\Tag $tag */
        $tag = $form->getObject();

        if (!$tag->getId()) {
            $this->tagService->createNewTag($tag);
        } else {
            $this->tagService->updateTag($tag);
        }

        return true;
    }

    /**
     * @return FormInterface
     */
    protected function getCrudForm()
    {
        $tagId = $this->getRequest()->getQuery('id');

        if (empty($tagId)) {
            $tag = new TagEntity();
        } else {
            $tag = $this->tagService->getTagById($tagId);
        }

        $this->tagForm->bind($tag);

        return $this->tagForm;
    }

    protected function getCrudSuccessResponse()
    {
        return $this->redirect()->toRoute('tag');
    }

    protected function getCrudFailureResponse()
    {
        return $this->redirect()->toRoute('tag');
    }
}
