<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
try {
    $post = Mage::getModel('blog/post')->loadByIdentifier('Hello');

    $post->setData('identifier', 'saharathemes-extensions');
    $post->setData('title', 'saharathemes-extensions');
    $post->setData('status', '1');

    $post->setData('created_time', Mage::getModel('core/date')->gmtDate());
    $post->setData('update_time', null);
    $post->setData('user', 'saharathemes');
    $post->setData('update_user', 'saharathemes');

    $post->setData('meta_keywords', 'saharathemes-extensions, saharathemes');
    $post->setData('meta_description', 'saharathemes-extensions');


    $post->setData('post_content', '
<h1>Welcome to our magento themes !</h1>
<p>Plazathemes provide professional Magento Themes, Magento extensions, Magento templates you can choose many Magento Themes in various business lines.</p>
');

    $cats = Mage::getModel('blog/cat')->getCollection();
    foreach ($cats as $cat) {
        if ($cat->getIdentifier() == 'news') {
            $post->setData('cats', array($cat->getId()));
            break;
        }
    }

    $post->save();
} catch (Exception $e) {
    Mage::logException($e);
}

$installer->endSetup();