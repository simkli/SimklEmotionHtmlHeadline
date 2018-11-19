<?php

namespace SimklEmotionHtmlHeadline;

use Doctrine\DBAL\Connection;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Shopware-Plugin SimklEmotionHtmlHeadline.
 */
class SimklEmotionHtmlHeadline extends Plugin
{

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onPostDispatch',
            'Enlight_Controller_Action_PostDispatchSecure_Widgets' => 'onPostDispatch'
        ];
    }

    public function onPostDispatch(\Enlight_Event_EventArgs $args)
    {
        /** @var $controller \Enlight_Controller_Action */
        $controller = $args->getSubject();
        $view = $controller->View();
        $view->addTemplateDir(__DIR__ . '/Resources/views');
    }

    /**
    * @param ContainerBuilder $container
    */
    public function build(ContainerBuilder $container)
    {
        $container->setParameter('simkl_emotion_html_headline.plugin_dir', $this->getPath());
        parent::build($container);
    }


    public function install(InstallContext $context)
    {
        parent::install($context);

        /** @var Connection $connection */
        $connection = $this->container->get('dbal_connection');

        $exists = (bool) $connection->fetchColumn('SELECT COUNT(*) as count FROM s_library_component_field WHERE name=\'simkl_headline\'');

        if (!$exists) {
            // exception is unhandled. If this fails the installation process should fail, too
            $connection->exec("INSERT INTO `s_library_component_field` (`id`, `componentID`, `name`, `x_type`, `value_type`, `field_label`, `support_text`, `help_title`, `help_text`, `store`, `display_field`, `value_field`, `default_value`, `allow_blank`, `translatable`, `position`) VALUES (NULL, (SELECT id FROM s_library_component WHERE x_type = 'emotion-components-html-element'), 'simkl_headline', 'textfield', '', 'Headline', 'Enter one valid HTML headline tag (e.g. h1, h2, h3, ...)', '', '', '', '', '', '', '1', '0', '10');");
        }
    }

    public function uninstall(UninstallContext $context)
    {
        if (!$context->keepUserData()) {
            /** @var Connection $connection */
            $connection = $this->container->get('dbal_connection');

            $connection->exec('DELETE FROM s_library_component_field WHERE name=\'simkl_headline\'');
        }

        parent::uninstall($context);
    }


}
