<?php
$settings = array(
    'base_path' => dirname(__FILE__) . '/../../../data/lwcfilemanager',
    'cdn_hostname' => 'cdn.example.com'
);

return array(
    'lwcfilemanager' => $settings,
    'service_manager' => array(
        'factories' => array(
            'LwcFilemanager\Service\Filemanager' => 'LwcFilemanager\Service\FilemanagerFactory',
        )
    ),
    'router' => array(
        'routes' => array(
            'zfcadmin' => array(
                'child_routes' => array(
                    'lwcfilemanager' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/files[/:action]',
                            'defaults' => array(
                                'controller' => 'LwcFilemanager\Controller\Admin',
                                'action' => 'index'
                            )
                        )
                    ),
                    'lwcfilemanager_ajax' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/files/ajax/:action',
                            'defaults' => array(
                                'controller' => 'LwcFilemanager\Controller\Ajax'
                            )
                        )
                    )
                )
            )
        )
    ),
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\Guard\Route' => array(
                array(
                    'route' => 'zfcadmin/lwcfilemanager', 'roles' => array('user')
                ),
                array(
                    'route' => 'zfcadmin/lwcfilemanager_ajax', 'roles' => array('user')
                ),
            )
        )
    ),
    'controllers' => array(
        'factories' => array(
            'LwcFilemanager\Controller\Admin' => 'LwcFilemanager\Controller\AdminControllerFactory',
        ),
        'invokables' => array(
            'LwcFilemanager\Controller\Ajax' => 'LwcFilemanager\Controller\AjaxController',
        )
    ),
    'navigation' => array(
        'admin' => array(
            'files' => array(
                'type' => 'mvc',
                'route' => 'zfcadmin/lwcfilemanager',
                'label' => 'File Manager'
            )
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'fileSize' => 'LwcFilemanager\View\Helper\Filesize',
            'fileList' => 'LwcFilemanager\View\Helper\Admin\Filelist',
            'fileIcon' => 'LwcFilemanager\View\Helper\Admin\Icon'
        ),
        'factories' => array(
            'cdnUrl' => 'LwcFilemanager\View\Helper\CdnUrlFactory',
            'backLink' => 'LwcFilemanager\View\Helper\BackLinkFactory',
        )
    ),
    'view_manager' => array(
        'template_map' => array(
            'lwcfilemanager/filelist' => __DIR__ . '/../view/lwc-filemanager/admin/filelist.phtml',
            'lwcfilemanager/popup_template' => __DIR__ . '/../view/lwc-filemanager/admin/popup/template.phtml',
            'lwcfilemanager/popup_new_folder' => __DIR__ . '/../view/lwc-filemanager/admin/popup/new-folder.phtml',
            'lwcfilemanager/popup_new_files' => __DIR__ . '/../view/lwc-filemanager/admin/popup/new-files.phtml',
            'lwcfilemanager/popup_picker' => __DIR__ . '/../view/lwc-filemanager/admin/popup/picker.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'translator' => array(
        'locale' => 'de_DE',
        'translation_file_patterns' => array(
            array(
                'type' => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.php'
            )
        )
    )
);