<?php
namespace AppBundle\Block;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

use Sonata\AdminBundle\Form\FormMapper;
Use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Admin\Pool;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\VarDumper\VarDumper;


/**
 * Class StatBlockService
 * @package AppBundle\Block\StatBlockService
 */
class StatBlockService extends BaseBlockService
{
    /**
    * @var SecurityContextInterface
    */
    protected $securityContext;

    /**
    * @var EntityManager
    */
    protected $em;

    public function __construct($name, EngineInterface $templating, Pool $pool,  EntityManager $em,  $securityContext)
    {
        parent::__construct($name, $templating);

        $this->pool = $pool;
        $this->em = $em;
        $this->securityContext = $securityContext;
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return 'Statistique';
    }


    /**
    * {@inheritdoc}
    */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {

        $settings = $this->getDefaultSettings();
        //$user_current   = $this->securityContext->getToken()->getUser();
        //$user_id         = $user_current->getId();

        $repository = $this->em->getRepository('AppBundle:Film');
        $films = $repository->findAll();

        return $this->renderResponse($settings['template'], array(
            'count'         => count($films),
            'block'         => $blockContext->getBlock(),
            'base_template' => $this->pool->getTemplate('layout'),
            'settings'      => $settings,
        ), $response);
    }
    /**
    * {@inheritdoc}
    */
    public function getDefaultSettings()
    {
        return array(
            'title'    => 'Films dans la database',
            'template' => 'block/statistique.html.twig',
        );
    }
}
