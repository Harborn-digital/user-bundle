<?php

if (class_exists('Doctrine\\Common\\Persistence\\ManagerRegistry')) {
    class_alias('Doctrine\\Common\\Persistence\\ManagerRegistry', 'Doctrine\\Persistence\\ManagerRegistry', false);
    class_alias('Doctrine\\Common\\Persistence\\ObjectRepository', 'Doctrine\\Persistence\\ObjectRepository', false);
    class_alias('Doctrine\\Common\\Persistence\\ObjectManager', 'Doctrine\\Persistence\\ObjectManager', false);
}
