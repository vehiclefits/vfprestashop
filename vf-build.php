<?php
if(!isset($argv[1])) {
  exit('Must pass a version as argument');
}
$version = $argv[1];
$buildPath = '/tmp/vfprestashop-'.$version;
passthru("rsync -avr --exclude='.idea' --exclude='.gitignore' --exclude='.travis.yml' --exclude='README.md' --exclude='vendor' --exclude='phpunit.*' --exclude='vf-build.php' --exclude='.git' --exclude='*Test.php' --delete-after . $buildPath");
passthru("cd $buildPath/modules/vaf; composer install");
passthru("cd $buildPath/; git clone git@github.com:vehiclefits/vfadmin.git; cd vfadmin; rm -rf .git");