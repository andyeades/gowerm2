<?php

declare(strict_types=1);

namespace PackageVersions;

use Composer\InstalledVersions;
use OutOfBoundsException;

class_exists(InstalledVersions::class);

/**
 * This class is generated by composer/package-versions-deprecated, specifically by
 * @see \PackageVersions\Installer
 *
 * This file is overwritten at every run of `composer install` or `composer update`.
 *
 * @deprecated in favor of the Composer\InstalledVersions class provided by Composer 2. Require composer-runtime-api:^2 to ensure it is present.
 */
final class Versions
{
    /**
     * @deprecated please use {@see self::rootPackageName()} instead.
     *             This constant will be removed in version 2.0.0.
     */
    const ROOT_PACKAGE_NAME = 'magento/project-community-edition';

    /**
     * Array of all available composer packages.
     * Dont read this array from your calling code, but use the \PackageVersions\Versions::getVersion() method instead.
     *
     * @var array<string, string>
     * @internal
     */
    const VERSIONS          = array (
  'bacon/bacon-qr-code' => '1.0.3@5a91b62b9d37cee635bbf8d553f4546057250bee',
  'bcncommerce/json-stream' => '0.4.1@695936718d03259706b6f101349165320b7d0d50',
  'beberlei/assert' => 'v2.9.9@124317de301b7c91d5fce34c98bba2c6925bec95',
  'box/spout' => 'v2.7.3@3681a3421a868ab9a65da156c554f756541f452b',
  'christian-riesen/base32' => '1.6.0@2e82dab3baa008e24a505649b0d583c31d31e894',
  'clue/stream-filter' => 'v1.5.0@aeb7d8ea49c7963d3b581378955dbf5bc49aa320',
  'colinmollenhour/cache-backend-file' => 'v1.4.5@03c7d4c0f43b2de1b559a3527d18ff697d306544',
  'colinmollenhour/cache-backend-redis' => '1.10.6@cc941a5f4cc017e11d3eab9061811ba9583ed6bf',
  'colinmollenhour/credis' => '1.10.0@8ab6db707c821055f9856b8cf76d5f44beb6fd8a',
  'colinmollenhour/php-redis-session-abstract' => 'v1.4.4@8d684bbacac99450f2a9ddf6f56be296997e2959',
  'composer/ca-bundle' => '1.2.10@9fdb22c2e97a614657716178093cd1da90a64aa8',
  'composer/composer' => '1.10.22@28c9dfbe2351635961f670773e8d7b17bc5eda25',
  'composer/package-versions-deprecated' => '1.11.99.2@c6522afe5540d5fc46675043d3ed5a45a740b27c',
  'composer/semver' => '1.7.2@647490bbcaf7fc4891c58f47b825eb99d19c377a',
  'composer/spdx-licenses' => '1.5.5@de30328a7af8680efdc03e396aad24befd513200',
  'composer/xdebug-handler' => '1.4.6@f27e06cd9675801df441b3656569b328e04aa37c',
  'container-interop/container-interop' => '1.2.0@79cbf1341c22ec75643d841642dd5d6acd83bdb8',
  'cweagans/composer-patches' => '1.7.1@9888dcc74993c030b75f3dd548bb5e20cdbd740c',
  'deployer/dist' => 'v6.8.0@857158fa5466d5135ce87f07fe67779b6b6a13d2',
  'donatj/phpuseragentparser' => 'v0.20.0@5992d7836868b408e73d0d490c779dda7a39135e',
  'elasticsearch/elasticsearch' => 'v7.11.0@277cd5e182827c59c23e146a836a30470c0f879d',
  'elevate/basetheme' => 'dev-master@a6adbe36dc969c93d646269eae956186ca9267dd',
  'elevate/deployer-magento2' => 'dev-master@8485bbc41c3b920e2afcc75f8bbf29bef2b324fd',
  'elevate/elevate-addtocart' => 'dev-master@d4e72511a00f0525f6ed59936c4640ecdf6db20b',
  'elevate/elevate-cartassignments' => 'dev-master@89f7a9a41008b9a31a2f44e1950ca2175c661d70',
  'elevate/elevate-core' => 'dev-master@f3725733887a2d76b17233e7b86c2e51c8328e0b',
  'elevate/elevate-couponmessages' => 'dev-master@f75a443f3ee0929c2e525ed00ca5c20ff0ad1061',
  'elevate/elevate-csp' => 'dev-master@69a41f8552ff94730344876d9ae28e3a756dfe3d',
  'elevate/elevate-customadmin' => 'dev-master@43ea9d2bfc75e3d8730943a020b8441315df715d',
  'elevate/elevate-framework' => 'dev-master@49f984daa11baf76128ed7012eccd9f30946c65d',
  'elevate/elevate-megamenu' => 'dev-master@9642ef0b3d5fca8ca71c9662665f22026164d0f8',
  'elevate/elevate-release' => 'dev-master@0506def9db36105549464f4f6f6089aaf6cda30a',
  'elevate/elevate-relprevnext' => 'dev-master@fcf84aa9ca010c8a1f629476b4742dd44d80aac2',
  'elevate/elevate-reviews' => 'dev-master@7533ec237730440f30b2702bb0da6644a5224643',
  'elevate/elevate-shopall' => 'dev-master@30392fa9cf88097933355032af7a2b93968434c4',
  'elevate/module-advancedsorting' => 'dev-master@0e926cc5f631c18fd61c9cc8e77187ef5345b1a8',
  'elevate/module-bundleadvanced' => 'dev-master@0cc0cba5c91c760ba753f3f7d079a2f41a95a707',
  'elevate/module-cookie-notice' => 'dev-master@ad5399e8d7ae56aa41e993e5c1a20582c40e7723',
  'elevate/module-customergallery' => 'dev-master@dde88622d3c91a8dca55fc97c7b1d6a836894a7a',
  'elevate/module-datafeeds' => 'dev-master@052f9ad6502789f76dcec3c88eaf69293254dc0a',
  'elevate/module-delivery' => 'dev-master@cf12ef628ef8cb54bd7176bc5aa3e22c88dc0404',
  'elevate/module-discontinuedproducts' => 'dev-master@f9862203a244dea6b19e9f55741e4f633db086e5',
  'elevate/module-emailpreview' => 'dev-master@44acea312ae83a8a95433ac9a88bb6899abb7fdc',
  'elevate/module-landingpages' => 'dev-master@2b707405a2bea6d75d203dcb09e35dc61679711c',
  'elevate/module-landingpages-rewrite' => 'dev-master@cd15b437ba1f1d2f03ad82981e24fb70fdf22189',
  'elevate/module-linkedproducts' => 'dev-master@d62d0cdf9d333a76dcb936764c262af75cf34137',
  'elevate/module-mobiledetect' => 'dev-master@3d31a0fafdd5b81feb6afca8f76f1d508dad2b75',
  'elevate/module-performance-dashboard' => 'dev-master@b9d48782438320c031229946c51e1cb75c767f8f',
  'elevate/module-printlabels' => 'dev-master@6e4dab56a4e07c290aaea7cd3de7be6ba8867822',
  'elevate/module-productkeyfacts' => 'dev-master@21419d3b1f3a8bcfe92081ef0c3f0f1b7f97bd8c',
  'elevate/module-promotions' => 'dev-master@83d6ad80283ff9f3b1f55e58b79886897d946089',
  'elevate/module-shell' => 'dev-master@9f2e8ebdff46824b393d9b6cddb19bd66c844514',
  'elevate/module-support' => 'dev-master@703061187e07509c6699fae2afa576dd02cc656e',
  'elevate/module-theme-options' => 'dev-master@289394537a9849155f4ba26132e94af6f6a61e4f',
  'elevate/module-trackorder' => 'dev-master@3eeb7c8855e7a677a8a2d281fa633a44cf466c5d',
  'elevate/module-widgets' => 'dev-master@c1ccd7adc466b648de6e51711fa44f3d0ed96f58',
  'elevate/producticons' => 'dev-master@e7825ec38f5cfc21f7544345554df6c9c47bc29f',
  'elevate/theme' => 'dev-master@cdda89e107522c59ceef05b0d0a54eeffa1d162d',
  'elevate_client/aitoc-core' => 'dev-master@d9c6b9a746b8122fa5a841b072c8feb037b6d805',
  'elevate_client/aitoc-options-management' => 'dev-master@f435960de15cb708c55888e5f4d99ec13ed22192',
  'elevate_external/aheadworks-product-question' => 'dev-master@005e1bf0b845979a8abbf46112504c504f3beb4e',
  'elevate_external/amasty-base' => 'dev-master@0a83d5bfb3670976506902c75ea52ad67ca0363a',
  'elevate_external/amasty-cron-schedule-list' => 'dev-master@5877005b962ec9cfe155310038d157c5f3ef1b7c',
  'elevate_external/amasty-xnotif' => 'dev-master@634a9f2d17c622ec166d062165cb2582003c8c73',
  'elevate_external/apptrian-facebook-pixel' => 'dev-master@835a8d38951fff3455aa877f96437e515cc33533',
  'elevate_external/awin-advertisertracking' => 'dev-master@bda6f0b41b227d0b34e6f0edd1d295815be34f2f',
  'elevate_external/bss-reindex' => 'dev-master@dbcca96c37bf209aea4a84c2a87d5a2b16f74594',
  'elevate_external/bsscommerce-adminpaymentmethod' => 'dev-master@ecd4d54ca5d317e7fd4ee5bc0dc9c33b21c97f6b',
  'elevate_external/bsscommerce-adminshippingmethod' => 'dev-master@a93b7af61a632a82541d7f68400f29961ed137df',
  'elevate_external/craftyclicks-ukpostcodelookup' => 'dev-master@b449c1a6d3696bb3b3566d3ed60aa366849bb44e',
  'elevate_external/degdigital-customreports' => 'dev-master@ecdd42d6aa0cb0ac47c38e2b13d9660172022175',
  'elevate_external/firebear-importexport' => 'dev-master@4becebd8280a56734fe28572096c90261fc30294',
  'elevate_external/firebear_configurableproducts' => 'dev-master@de6089f90b7d7440da38fa5ec4c37bcf5d5825ea',
  'elevate_external/fisheye-lazyload' => 'dev-master@64a862abedadbb2c0c7e2bdeee5a6194ea02be34',
  'elevate_external/justbetter-sentry' => 'dev-master@c0ded198b537714087d43c84a1d6b3b9fc1271f5',
  'elevate_external/m2-weltpixel-backend' => 'dev-master@a12bef4c10af609aab2d05a2e0266c1a075ee792',
  'elevate_external/magecomp-order-comments' => 'dev-master@8ac08af9e343d5a731ee80c343a12c8e173a611b',
  'elevate_external/mageme_module-webforms' => 'dev-master@728e3e5580485eea77600bf37647742f93cbe24f',
  'elevate_external/magepal-edit-order-email' => 'dev-master@b2fe0018934560f9124e6802bc82b3c68237eca9',
  'elevate_external/magepal-preview-checkout-success-page' => 'dev-master@ddf55d1d075ca3d028e9f811c358bb215fe6347b',
  'elevate_external/mageplaza-seo' => 'dev-master@7d68903bbc0d12668d755dd4f5d6e4489b297d32',
  'elevate_external/mageplaza-sitemap' => 'dev-master@2e861040b554b92453f71800d065a6b0c0b1336e',
  'elevate_external/mageplaza-smtp' => 'dev-master@855cf2b547ee3a21001c7fa4babd79ce1d60ecdf',
  'elevate_external/mageplaza_blog' => 'dev-master@77c6e727de1f99d47d09a0780d7fe5c3be705a26',
  'elevate_external/mageplaza_core' => 'dev-master@eaa003baa1a261b099197cc17f3029e7cf339908',
  'elevate_external/mageside-customshippingprice' => 'dev-master@3839a08d96b1f1cd29df9aaa2adda67b40d0c2e3',
  'elevate_external/mageworx-module-alsobought' => 'dev-master@1e74016b1034fd924e235f703c3b2cb3f9af456a',
  'elevate_external/magictoolbox-magiczoomplus' => 'dev-master@962ef88c63a95339fd2da94dcf7cad26e8955482',
  'elevate_external/mirasvit_core' => 'dev-master@7f773b8bf744fdea455785373f2a686569a4e91c',
  'elevate_external/mirasvit_kb' => 'dev-master@7ace83bdb65071e4d86233a20cda010c60cbac51',
  'elevate_external/module-fme-productattachments' => 'dev-master@b125134beed723d001096f74f298fa82e15d399d',
  'elevate_external/module-stripeintegration-payments' => 'dev-master@98ad1da50f1c91b2dc202d0d249b16e54ca6f80b',
  'elevate_external/potato-compressor' => 'dev-master@79e0563e61d8147ea47b2c5e745238d265ad0a35',
  'elevate_external/smile-debug-toolbar' => 'dev-master@8b7860bcfee3585e2a69c113a2e0920b5ac17179',
  'elevate_external/solwin-soldout' => 'dev-master@b31bdb111ed1f41a9ee8ad52b168ced52ff72a49',
  'elevate_external/tigren-progressivewebapp' => 'dev-master@be7d2a5fa388a5e8b74a59efac85bb46a1271fe2',
  'elevate_external/webshopapps-matrixrate' => 'dev-master@5003ed3ef1dce933957186b781ce376fc9f9e038',
  'elevate_external/weltpixel-google-tag-manager' => 'dev-master@bd228820afbea293f933f2e76c0aab7ae506e402',
  'endroid/qr-code' => '2.5.0@a9a57ab57ac75928fcdcfb2a71179963ff6fe573',
  'ezimuel/guzzlestreams' => '3.0.1@abe3791d231167f14eb80d413420d1eab91163a8',
  'ezimuel/ringphp' => '1.1.2@0b78f89d8e0bb9e380046c31adfa40347e9f663b',
  'firebase/php-jwt' => 'v5.4.0@d2113d9b2e0e349796e72d2a63cf9319100382d2',
  'gene/module-braintree' => 'dev-master@9ec2a228e568177c7a1135332a56f27696e634b9',
  'google/apiclient' => 'v2.10.1@11871e94006ce7a419bb6124d51b6f9ace3f679b',
  'google/apiclient-services' => 'v0.203.0@e397f35251a49e0f4284d5f7d934164ca1274066',
  'google/auth' => 'v1.16.0@c747738d2dd450f541f09f26510198fbedd1c8a0',
  'google/recaptcha' => '1.2.4@614f25a9038be4f3f2da7cbfd778dc5b357d2419',
  'guzzlehttp/guzzle' => '6.5.5@9d4290de1cfd701f38099ef7e183b64b4b7b0c5e',
  'guzzlehttp/promises' => '1.4.1@8e7d04f1f6450fef59366c399cfad4b9383aa30d',
  'guzzlehttp/psr7' => '1.8.2@dc960a912984efb74d0a90222870c72c87f10c91',
  'http-interop/http-factory-guzzle' => '1.0.0@34861658efb9899a6618cef03de46e2a52c80fc0',
  'jean85/pretty-package-versions' => '1.6.0@1e0104b46f045868f11942aea058cd7186d6c303',
  'justinrainbow/json-schema' => '5.2.10@2ba9c8c862ecd5510ed16c6340aa9f6eadb4f31b',
  'khanamiryan/qrcode-detector-decoder' => '1.0.5.1@b96163d4f074970dfe67d4185e75e1f4541b30ca',
  'laminas/laminas-captcha' => '2.9.0@b88f650f3adf2d902ef56f6377cceb5cd87b9876',
  'laminas/laminas-code' => '3.3.2@128784abc7a0d9e1fcc30c446533aa6f1db1f999',
  'laminas/laminas-config' => '2.6.0@71ba6d5dd703196ce66b25abc4d772edb094dae1',
  'laminas/laminas-console' => '2.8.0@478a6ceac3e31fb38d6314088abda8b239ee23a5',
  'laminas/laminas-crypt' => '2.6.0@6f291fe90c84c74d737c9dc9b8f0ad2b55dc0567',
  'laminas/laminas-db' => '2.11.4@5b59413b8dd5d79e3fe58c2650c60b1730989f36',
  'laminas/laminas-dependency-plugin' => '1.0.4@38bf91861f5b4d49f9a1c530327c997f7a7fb2db',
  'laminas/laminas-di' => '2.6.1@239b22408a1f8eacda6fc2b838b5065c4cf1d88e',
  'laminas/laminas-diactoros' => '1.8.7p2@6991c1af7c8d2c8efee81b22ba97024781824aaa',
  'laminas/laminas-escaper' => '2.6.1@25f2a053eadfa92ddacb609dcbbc39362610da70',
  'laminas/laminas-eventmanager' => '3.2.1@ce4dc0bdf3b14b7f9815775af9dfee80a63b4748',
  'laminas/laminas-feed' => '2.12.3@3c91415633cb1be6f9d78683d69b7dcbfe6b4012',
  'laminas/laminas-filter' => '2.9.4@3c4476e772a062cef7531c6793377ae585d89c82',
  'laminas/laminas-form' => '2.15.1@37c5f5ac9240159f5d93f52367d0e57fa96f9b22',
  'laminas/laminas-http' => '2.13.0@33b7942f51ce905ce9bfc8bf28badc501d3904b5',
  'laminas/laminas-hydrator' => '2.4.2@4a0e81cf05f32edcace817f1f48cb4055f689d85',
  'laminas/laminas-i18n' => '2.10.3@94ff957a1366f5be94f3d3a9b89b50386649e3ae',
  'laminas/laminas-inputfilter' => '2.10.1@b29ce8f512c966468eee37ea4873ae5fb545d00a',
  'laminas/laminas-json' => '2.6.1@db58425b7f0eba44a7539450cc926af80915951a',
  'laminas/laminas-loader' => '2.6.1@5d01c2c237ae9e68bec262f339947e2ea18979bc',
  'laminas/laminas-log' => '2.12.0@4e92d841b48868714a070b10866e94be80fc92ff',
  'laminas/laminas-mail' => '2.12.5@ed5b36a0deef4ffafe6138c2ae9cafcffafab856',
  'laminas/laminas-math' => '2.7.1@8027b37e00accc43f28605c7d8fd081baed1f475',
  'laminas/laminas-mime' => '2.7.4@e45a7d856bf7b4a7b5bd00d6371f9961dc233add',
  'laminas/laminas-modulemanager' => '2.9.0@789bbd4ab391da9221f265f6bb2d594f8f11855b',
  'laminas/laminas-mvc' => '2.7.15@7e7198b03556a57fb5fd3ed919d9e1cf71500642',
  'laminas/laminas-psr7bridge' => '0.2.2@14780ef1d40effd59d77ab29c6d439b2af42cdfa',
  'laminas/laminas-serializer' => '2.10.0@1c57f1bdf05da078493b774c9e8d77ee8b46b4bb',
  'laminas/laminas-server' => '2.8.1@4aaca9174c40a2fab2e2aa77999da99f71bdd88e',
  'laminas/laminas-servicemanager' => '2.7.11@841abb656c6018afebeec1f355be438426d6a3dd',
  'laminas/laminas-session' => '2.9.3@519e8966146536cd97c1cc3d59a21b095fb814d7',
  'laminas/laminas-soap' => '2.8.0@34f91d5c4c0a78bc5689cca2d1eaf829b27edd72',
  'laminas/laminas-stdlib' => '3.2.1@2b18347625a2f06a1a485acfbc870f699dbe51c6',
  'laminas/laminas-text' => '2.7.1@3601b5eacb06ed0a12f658df860cc0f9613cf4db',
  'laminas/laminas-uri' => '2.7.1@6be8ce19622f359b048ce4faebf1aa1bca73a7ff',
  'laminas/laminas-validator' => '2.13.5@d334dddda43af263d6a7e5024fd2b013cb6981f7',
  'laminas/laminas-view' => '2.11.5@16611035d7b3a6ef2c636a9268c213146123b663',
  'laminas/laminas-zendframework-bridge' => '1.1.1@6ede70583e101030bcace4dcddd648f760ddf642',
  'league/iso3166' => '2.1.5@aed3b32fc293afdf2c6c6a322c2408eb5d20804a',
  'magento/composer' => '1.5.1@',
  'magento/data-migration-tool' => '2.3.5@',
  'magento/framework' => '102.0.5@',
  'magento/framework-amqp' => '100.3.5@',
  'magento/framework-bulk' => '100.3.5@',
  'magento/framework-message-queue' => '100.3.5@',
  'magento/language-de_de' => '100.3.4@',
  'magento/language-en_us' => '100.3.4@',
  'magento/language-es_es' => '100.3.4@',
  'magento/language-fr_fr' => '100.3.4@',
  'magento/language-nl_nl' => '100.3.4@',
  'magento/language-pt_br' => '100.3.4@',
  'magento/language-zh_hans_cn' => '100.3.4@',
  'magento/magento-composer-installer' => '0.2.1@b9f929f718ef93ed61b6410bad85d40c37fd5ed3',
  'magento/magento2-base' => '2.3.5@',
  'magento/module-admin-analytics' => '100.3.2@',
  'magento/module-admin-notification' => '100.3.5@',
  'magento/module-advanced-pricing-import-export' => '100.3.4@',
  'magento/module-advanced-search' => '100.3.4@',
  'magento/module-asynchronous-operations' => '100.3.5@',
  'magento/module-authorization' => '100.3.4@',
  'magento/module-backend' => '101.0.5@',
  'magento/module-backup' => '100.3.5@',
  'magento/module-braintree-graph-ql' => '100.3.2@',
  'magento/module-bundle' => '100.3.5@',
  'magento/module-bundle-import-export' => '100.3.4@',
  'magento/module-cache-invalidate' => '100.3.4@',
  'magento/module-captcha' => '100.3.5@',
  'magento/module-catalog' => '103.0.5@',
  'magento/module-catalog-import-export' => '101.0.5@',
  'magento/module-catalog-inventory' => '100.3.5@',
  'magento/module-catalog-rule' => '101.1.5@',
  'magento/module-catalog-rule-configurable' => '100.3.5@',
  'magento/module-catalog-search' => '101.0.5@',
  'magento/module-catalog-url-rewrite' => '100.3.5@',
  'magento/module-catalog-widget' => '100.3.5@',
  'magento/module-checkout' => '100.3.5@',
  'magento/module-checkout-agreements' => '100.3.5@',
  'magento/module-cms' => '103.0.5@',
  'magento/module-cms-url-rewrite' => '100.3.4@',
  'magento/module-config' => '101.1.5@',
  'magento/module-configurable-import-export' => '100.3.4@',
  'magento/module-configurable-product' => '100.3.5@',
  'magento/module-configurable-product-sales' => '100.3.4@',
  'magento/module-contact' => '100.3.5@',
  'magento/module-cookie' => '100.3.5@',
  'magento/module-cron' => '100.3.5@',
  'magento/module-csp' => '100.3.0@',
  'magento/module-currency-symbol' => '100.3.5@',
  'magento/module-customer' => '102.0.5@',
  'magento/module-customer-import-export' => '100.3.5@',
  'magento/module-deploy' => '100.3.4@',
  'magento/module-developer' => '100.3.5@',
  'magento/module-directory' => '100.3.5@',
  'magento/module-downloadable' => '100.3.5@',
  'magento/module-downloadable-import-export' => '100.3.5@',
  'magento/module-eav' => '102.0.5@',
  'magento/module-elasticsearch' => '100.3.5@',
  'magento/module-elasticsearch-6' => '100.3.4@',
  'magento/module-elasticsearch-7' => '100.3.0@',
  'magento/module-email' => '101.0.5@',
  'magento/module-encryption-key' => '100.3.5@',
  'magento/module-gift-message' => '100.3.4@',
  'magento/module-google-analytics' => '100.3.4@',
  'magento/module-grouped-catalog-inventory' => '100.3.3@',
  'magento/module-grouped-import-export' => '100.3.4@',
  'magento/module-grouped-product' => '100.3.5@',
  'magento/module-import-export' => '100.3.5@',
  'magento/module-indexer' => '100.3.5@',
  'magento/module-instant-purchase' => '100.3.5@',
  'magento/module-integration' => '100.3.5@',
  'magento/module-layered-navigation' => '100.3.4@',
  'magento/module-media-gallery' => '100.3.1@',
  'magento/module-media-gallery-api' => '100.3.0@',
  'magento/module-media-storage' => '100.3.5@',
  'magento/module-message-queue' => '100.3.4@',
  'magento/module-msrp' => '100.3.5@',
  'magento/module-msrp-configurable-product' => '100.3.3@',
  'magento/module-msrp-grouped-product' => '100.3.3@',
  'magento/module-multishipping' => '100.3.5@',
  'magento/module-mysql-mq' => '100.3.4@',
  'magento/module-newsletter' => '100.3.5@',
  'magento/module-offline-payments' => '100.3.4@',
  'magento/module-offline-shipping' => '100.3.5@',
  'magento/module-page-cache' => '100.3.5@',
  'magento/module-payment' => '100.3.5@',
  'magento/module-paypal' => '100.3.5@',
  'magento/module-paypal-captcha' => '100.3.2@',
  'magento/module-paypal-recaptcha' => '1.0.1@',
  'magento/module-persistent' => '100.3.5@',
  'magento/module-product-alert' => '100.3.5@',
  'magento/module-product-video' => '100.3.5@',
  'magento/module-quote' => '101.1.5@',
  'magento/module-release-notification' => '100.3.4@',
  'magento/module-reports' => '100.3.5@',
  'magento/module-require-js' => '100.3.4@',
  'magento/module-review' => '100.3.5@',
  'magento/module-robots' => '101.0.4@',
  'magento/module-rss' => '100.3.4@',
  'magento/module-rule' => '100.3.5@',
  'magento/module-sales' => '102.0.5@',
  'magento/module-sales-inventory' => '100.3.4@',
  'magento/module-sales-rule' => '101.1.5@',
  'magento/module-sales-sequence' => '100.3.4@',
  'magento/module-search' => '101.0.5@',
  'magento/module-security' => '100.3.5@',
  'magento/module-send-friend' => '100.3.4@',
  'magento/module-shipping' => '100.3.5@',
  'magento/module-sitemap' => '100.3.5@',
  'magento/module-store' => '101.0.5@',
  'magento/module-swatches' => '100.3.5@',
  'magento/module-swatches-layered-navigation' => '100.3.4@',
  'magento/module-tax' => '100.3.5@',
  'magento/module-theme' => '101.0.5@',
  'magento/module-tinymce-3' => '100.3.5@',
  'magento/module-translation' => '100.3.5@',
  'magento/module-ui' => '101.1.5@',
  'magento/module-url-rewrite' => '101.1.5@',
  'magento/module-user' => '101.1.5@',
  'magento/module-variable' => '100.3.4@',
  'magento/module-vault' => '101.1.5@',
  'magento/module-webapi' => '100.3.4@',
  'magento/module-webapi-async' => '100.3.5@',
  'magento/module-webapi-security' => '100.3.4@',
  'magento/module-weee' => '100.3.5@',
  'magento/module-widget' => '101.1.4@',
  'magento/module-wishlist' => '101.1.5@',
  'magento/product-community-edition' => '2.3.5@',
  'magento/theme-adminhtml-backend' => '100.3.5@',
  'magento/theme-frontend-blank' => '100.3.5@',
  'magento/theme-frontend-luma' => '100.3.5@',
  'magento/zendframework1' => '1.14.5@6ad81500d33f085ca2391f2b59e37bd34203b29b',
  'monolog/monolog' => '1.26.1@c6b00f05152ae2c9b04a448f99c7590beb6042f5',
  'mpdf/mpdf' => 'v8.0.11@af17afbbfa0b6ce76defc8da5d02a73d54f94c64',
  'msp/recaptcha' => '2.2.3@',
  'msp/twofactorauth' => '3.1.2@',
  'myclabs/deep-copy' => '1.10.2@776f831124e9c62e1a2c601ecc52e776d8bb7220',
  'myclabs/php-enum' => '1.7.7@d178027d1e679832db9f38248fcc7200647dc2b7',
  'netresearch/jsonmapper' => 'v1.6.0@0d4d1b48d682a93b6bfedf60b88c7750e9cb0b06',
  'nyholm/psr7' => '1.4.1@2212385b47153ea71b1c1b1374f8cb5e4f7892ec',
  'paragonie/constant_time_encoding' => 'v2.4.0@f34c2b11eb9d2c9318e13540a1dbc2a3afbd939c',
  'paragonie/random_compat' => 'v9.99.99@84b4dfb120c6f9b4ff7b3685f9b8f1aa365a0c95',
  'paragonie/sodium_compat' => 'v1.16.1@2e856afe80bfc968b47da1f4a7e1ea8f03d06b38',
  'pelago/emogrifier' => 'v2.2.0@2472bc1c3a2dee8915ecc2256139c6100024332f',
  'php-amqplib/php-amqplib' => 'v2.10.1@6e2b2501e021e994fb64429e5a78118f83b5c200',
  'php-http/client-common' => '2.4.0@29e0c60d982f04017069483e832b92074d0a90b2',
  'php-http/discovery' => '1.14.0@778f722e29250c1fac0bbdef2c122fa5d038c9eb',
  'php-http/httplug' => '2.2.0@191a0a1b41ed026b717421931f8d3bd2514ffbf9',
  'php-http/message' => '1.11.1@887734d9c515ad9a564f6581a682fff87a6253cc',
  'php-http/message-factory' => 'v1.0.2@a478cb11f66a6ac48d8954216cfed9aa06a501a1',
  'php-http/promise' => '1.1.0@4c4c1f9b7289a2ec57cde7f1e9762a5789506f88',
  'phpseclib/mcrypt_compat' => '1.0.8@f74c7b1897b62f08f268184b8bb98d9d9ab723b0',
  'phpseclib/phpseclib' => '2.0.32@f5c4c19880d45d0be3e7d24ae8ac434844a898cd',
  'printnode/printnode-php' => '2.0.0-rc1@db904ff59438ec630be9bd51a545552d749483b3',
  'psr/cache' => '1.0.1@d11b50ad223250cf17b86e38383413f5a6764bf8',
  'psr/container' => '1.1.1@8622567409010282b7aeebe4bb841fe98b58dcaf',
  'psr/http-client' => '1.0.1@2dfb5f6c5eff0e91e20e913f8c5452ed95b86621',
  'psr/http-factory' => '1.0.1@12ac7fcd07e5b077433f5f2bee95b3a771bf61be',
  'psr/http-message' => '1.0.1@f6561bf28d520154e4b0ec72be95418abe6d9363',
  'psr/log' => '1.1.4@d49695b909c3b7628b6289db5479a1c204601f11',
  'ralouphie/getallheaders' => '3.0.3@120b605dfeb996808c31b6477290a714d356e822',
  'ramsey/uuid' => '3.8.0@d09ea80159c1929d75b3f9c60504d613aeb4a1e3',
  'react/promise' => 'v2.8.0@f3cff96a19736714524ca0dd1d4130de73dbbbc4',
  'redjanym/php-firebase-cloud-messaging' => 'v1.1.6@8c54d9e0fe57b78e809fca1262a723a9f0ed5b78',
  'salsify/json-streaming-parser' => 'v6.0.3@0b6403c8a2b1fc4c5af77b5c0174c11bd958a9d2',
  'scssphp/scssphp' => 'v1.6.0@b83594e2323c5d6e80785df3f91b9d1d32aad530',
  'seld/jsonlint' => '1.8.3@9ad6ce79c342fbd44df10ea95511a1b24dee5b57',
  'seld/phar-utils' => '1.1.1@8674b1d84ffb47cc59a101f5d5a3b61e87d23796',
  'sentry/sdk' => '2.2.0@089858b1b27d3705a5fd1c32d8d10beb55980190',
  'sentry/sentry' => '2.5.2@ce63f13e2cf9f72ec169413545a3f7312b2e45e3',
  'setasign/fpdi' => 'v2.3.6@6231e315f73e4f62d72b73f3d6d78ff0eed93c31',
  'spomky-labs/otphp' => 'v8.3.3@eb14442699ae6470b29ffd89238a9ccfb9f20788',
  'stripe/stripe-php' => 'v7.89.0@fea12a15d46d15c017b3fe76f78aa7c983cb5928',
  'symfony/console' => 'v4.4.26@9aa1eb46c1b12fada74dc0c529e93d1ccef22576',
  'symfony/css-selector' => 'v4.4.25@c1e29de6dc893b130b45d20d8051efbb040560a9',
  'symfony/deprecation-contracts' => 'v2.4.0@5f38c8804a9e97d23e0c8d63341088cd8a22d627',
  'symfony/event-dispatcher' => 'v4.4.25@047773e7016e4fd45102cedf4bd2558ae0d0c32f',
  'symfony/event-dispatcher-contracts' => 'v1.1.9@84e23fdcd2517bf37aecbd16967e83f0caee25a7',
  'symfony/filesystem' => 'v4.4.26@a501126eb6ec9288a3434af01b3d4499ec1268a0',
  'symfony/finder' => 'v4.4.25@ed33314396d968a8936c95f5bd1b88bd3b3e94a3',
  'symfony/http-client' => 'v5.3.3@fde4bdb10bf197f932ebccfcb9982881d296fc4c',
  'symfony/http-client-contracts' => 'v2.4.0@7e82f6084d7cae521a75ef2cb5c9457bbda785f4',
  'symfony/options-resolver' => 'v4.4.25@2e607d627c70aa56284a02d34fc60dfe3a9a352e',
  'symfony/polyfill-ctype' => 'v1.23.0@46cd95797e9df938fdd2b03693b5fca5e64b01ce',
  'symfony/polyfill-intl-grapheme' => 'v1.23.0@24b72c6baa32c746a4d0840147c9715e42bb68ab',
  'symfony/polyfill-intl-idn' => 'v1.23.0@65bd267525e82759e7d8c4e8ceea44f398838e65',
  'symfony/polyfill-intl-normalizer' => 'v1.23.0@8590a5f561694770bdcd3f9b5c69dde6945028e8',
  'symfony/polyfill-mbstring' => 'v1.23.0@2df51500adbaebdc4c38dea4c89a2e131c45c8a1',
  'symfony/polyfill-php56' => 'v1.20.0@54b8cd7e6c1643d78d011f3be89f3ef1f9f4c675',
  'symfony/polyfill-php72' => 'v1.23.0@9a142215a36a3888e30d0a9eeea9766764e96976',
  'symfony/polyfill-php73' => 'v1.23.0@fba8933c384d6476ab14fb7b8526e5287ca7e010',
  'symfony/polyfill-php80' => 'v1.23.0@eca0bf41ed421bed1b57c4958bab16aa86b757d0',
  'symfony/polyfill-uuid' => 'v1.23.0@9165effa2eb8a31bb3fa608df9d529920d21ddd9',
  'symfony/process' => 'v4.4.26@7e812c84c3f2dba173d311de6e510edf701685a8',
  'symfony/property-access' => 'v5.3.0@8988399a556cffb0fba9bb3603f8d1ba4543eceb',
  'symfony/property-info' => 'v5.3.1@6f8bff281f215dbf41929c7ec6f8309cdc0912cf',
  'symfony/service-contracts' => 'v2.4.0@f040a30e04b57fbcc9c6cbcf4dbaa96bd318b9bb',
  'symfony/string' => 'v5.3.3@bd53358e3eccec6a670b5f33ab680d8dbe1d4ae1',
  'tcdent/php-restclient' => '0.1.7@4522e8518eaef770d715977fcb45f187f8ad7499',
  'tedivm/jshrink' => 'v1.3.3@566e0c731ba4e372be2de429ef7d54f4faf4477a',
  'tmwk/client-prestashop-api' => 'v1.0.3@b69d17f13966b850ad6f4c630d8c0cd9c8a76471',
  'true/punycode' => 'v2.1.1@a4d0c11a36dd7f4e7cd7096076cab6d3378a071e',
  'tubalmartin/cssmin' => 'v4.1.1@3cbf557f4079d83a06f9c3ff9b957c022d7805cf',
  'webimpress/safe-writer' => '2.1.0@5cfafdec5873c389036f14bf832a5efc9390dcdd',
  'webonyx/graphql-php' => 'v0.13.9@d9a94fddcad0a35d4bced212b8a44ad1bc59bdf3',
  'wikimedia/less.php' => '1.8.2@e238ad228d74b6ffd38209c799b34e9826909266',
  'yubico/u2flib-server' => '1.0.2@55d813acf68212ad2cadecde07551600d6971939',
  'allure-framework/allure-codeception' => '1.3.0@9d31d781b3622b028f1f6210bc76ba88438bd518',
  'allure-framework/allure-php-api' => '1.1.8@5ae2deac1c7e1b992cfa572167370de45bdd346d',
  'allure-framework/allure-phpunit' => '1.2.3@45504aeba41304cf155a898fa9ac1aae79f4a089',
  'behat/gherkin' => 'v4.8.0@2391482cd003dfdc36b679b27e9f5326bd656acd',
  'codeception/codeception' => '2.4.5@5fee32d5c82791548931cbc34806b4de6aa1abfc',
  'codeception/phpunit-wrapper' => '6.8.3@fa35d3ce09c2a591247732e0d11be08125c389d4',
  'codeception/stub' => '2.1.0@853657f988942f7afb69becf3fd0059f192c705a',
  'consolidation/annotated-command' => '4.2.4@ec297e05cb86557671c2d6cbb1bebba6c7ae2c60',
  'consolidation/config' => '1.2.1@cac1279bae7efb5c7fb2ca4c3ba4b8eb741a96c1',
  'consolidation/log' => '2.0.2@82a2aaaa621a7b976e50a745a8d249d5085ee2b1',
  'consolidation/output-formatters' => '4.1.2@5821e6ae076bf690058a4de6c94dce97398a69c9',
  'consolidation/robo' => '1.4.13@fd28dcca1b935950ece26e63541fbdeeb09f7343',
  'consolidation/self-update' => '1.2.0@dba6b2c0708f20fa3ba8008a2353b637578849b4',
  'dflydev/dot-access-data' => 'v1.1.0@3fbd874921ab2c041e899d044585a2ab9795df8a',
  'doctrine/annotations' => '1.13.1@e6e7b7d5b45a2f2abc5460cc6396480b2b1d321f',
  'doctrine/instantiator' => '1.4.0@d56bf6102915de5702778fe20f2de3b2fe570b5b',
  'doctrine/lexer' => '1.2.1@e864bbf5904cb8f5bb334f99209b48018522f042',
  'facebook/webdriver' => '1.7.1@e43de70f3c7166169d0f14a374505392734160e5',
  'flow/jsonpath' => '0.5.0@b9738858c75d008c1211612b973e9510f8b7f8ea',
  'friendsofphp/php-cs-fixer' => 'v2.14.6@8d18a8bb180e2acde1c8031db09aefb9b73f6127',
  'fzaninotto/faker' => 'v1.9.2@848d8125239d7dbf8ab25cb7f054f1a630e68c2e',
  'grasmash/expander' => '1.0.0@95d6037344a4be1dd5f8e0b0b2571a28c397578f',
  'grasmash/yaml-expander' => '1.4.0@3f0f6001ae707a24f4d9733958d77d92bf9693b1',
  'jms/metadata' => '1.7.0@e5854ab1aa643623dc64adde718a8eec32b957a8',
  'jms/parser-lib' => '1.0.0@c509473bc1b4866415627af0e1c6cc8ac97fa51d',
  'jms/serializer' => '1.14.1@ba908d278fff27ec01fb4349f372634ffcd697c0',
  'league/container' => '2.5.0@8438dc47a0674e3378bcce893a0a04d79a2c22b3',
  'lusitanian/oauth' => 'v0.8.11@fc11a53db4b66da555a6a11fce294f574a8374f9',
  'magento/magento-coding-standard' => '3@73a7b7f3c00b02242f45f706571430735586f608',
  'magento/magento2-functional-testing-framework' => '2.4.5@d9de524babec36919b3ef73f3af03fbce99d427a',
  'mikey179/vfsstream' => 'v1.6.9@2257e326dc3d0f50e55d0a90f71e37899f029718',
  'mustache/mustache' => 'v2.13.0@e95c5a008c23d3151d59ea72484d4f72049ab7f4',
  'pdepend/pdepend' => '2.5.2@9daf26d0368d4a12bed1cacae1a9f3a6f0adf239',
  'phar-io/manifest' => '1.0.1@2df402786ab5368a0169091f61a7c1e0eb6852d0',
  'phar-io/version' => '1.0.1@a70c0ced4be299a63d32fa96d9281d03e94041df',
  'php-cs-fixer/diff' => 'v1.3.1@dbd31aeb251639ac0b9e7e29405c1441907f5759',
  'phpcollection/phpcollection' => '0.5.0@f2bcff45c0da7c27991bbc1f90f47c4b7fb434a6',
  'phpdocumentor/reflection-common' => '2.2.0@1d01c49d4ed62f25aa84a747ad35d5a16924662b',
  'phpdocumentor/reflection-docblock' => '5.2.2@069a785b2141f5bcf49f3e353548dc1cce6df556',
  'phpdocumentor/type-resolver' => '1.4.0@6a467b8989322d92aa1c8bf2bebcc6e5c2ba55c0',
  'phpmd/phpmd' => '2.7.0@a05a999c644f4bc9a204846017db7bb7809fbe4c',
  'phpoption/phpoption' => '1.7.5@994ecccd8f3283ecf5ac33254543eb0ac946d525',
  'phpspec/prophecy' => 'v1.10.3@451c3cd1418cf640de218914901e51b064abb093',
  'phpunit/php-code-coverage' => '5.3.2@c89677919c5dd6d3b3852f230a663118762218ac',
  'phpunit/php-file-iterator' => '1.4.5@730b01bc3e867237eaac355e06a36b85dd93a8b4',
  'phpunit/php-text-template' => '1.2.1@31f8b717e51d9a2afca6c9f046f5d69fc27c8686',
  'phpunit/php-timer' => '1.0.9@3dcf38ca72b158baf0bc245e9184d3fdffa9c46f',
  'phpunit/php-token-stream' => '2.0.2@791198a2c6254db10131eecfe8c06670700904db',
  'phpunit/phpunit' => '6.5.14@bac23fe7ff13dbdb461481f706f0e9fe746334b7',
  'phpunit/phpunit-mock-objects' => '5.0.10@cd1cf05c553ecfec36b170070573e540b67d3f1f',
  'sebastian/code-unit-reverse-lookup' => '1.0.2@1de8cd5c010cb153fcd68b8d0f64606f523f7619',
  'sebastian/comparator' => '2.1.3@34369daee48eafb2651bea869b4b15d75ccc35f9',
  'sebastian/diff' => '2.0.1@347c1d8b49c5c3ee30c7040ea6fc446790e6bddd',
  'sebastian/environment' => '3.1.0@cd0871b3975fb7fc44d11314fd1ee20925fce4f5',
  'sebastian/exporter' => '3.1.3@6b853149eab67d4da22291d36f5b0631c0fd856e',
  'sebastian/finder-facade' => '1.2.3@167c45d131f7fc3d159f56f191a0a22228765e16',
  'sebastian/global-state' => '2.0.0@e8ba02eed7bbbb9e59e43dedd3dddeff4a56b0c4',
  'sebastian/object-enumerator' => '3.0.4@e67f6d32ebd0c749cf9d1dbd9f226c727043cdf2',
  'sebastian/object-reflector' => '1.1.2@9b8772b9cbd456ab45d4a598d2dd1a1bced6363d',
  'sebastian/phpcpd' => '3.0.1@dfed51c1288790fc957c9433e2f49ab152e8a564',
  'sebastian/recursion-context' => '3.0.1@367dcba38d6e1977be014dc4b22f47a484dac7fb',
  'sebastian/resource-operations' => '1.0.0@ce990bb21759f94aeafd30209e8cfcdfa8bc3f52',
  'sebastian/version' => '2.0.1@99732be0ddb3361e16ad77b68ba41efc8e979019',
  'squizlabs/php_codesniffer' => '3.4.2@b8a7362af1cc1aadb5bd36c3defc4dda2cf5f0a8',
  'symfony/browser-kit' => 'v4.4.25@729b1f0eca3ef18ea4e1a29b166145aff75d8fa1',
  'symfony/config' => 'v4.4.26@1cb26cdb8a9834d8494cadd284602fa0647b73e5',
  'symfony/dependency-injection' => 'v4.4.26@a944d2f8e903dc99f5f1baf3eb74081352f0067f',
  'symfony/dom-crawler' => 'v4.4.25@41d15bb6d6b95d2be763c514bb2494215d9c5eef',
  'symfony/http-foundation' => 'v5.3.3@0e45ab1574caa0460d9190871a8ce47539e40ccf',
  'symfony/polyfill-php70' => 'v1.20.0@5f03a781d984aae42cebd18e7912fa80f02ee644',
  'symfony/polyfill-php81' => 'v1.23.0@e66119f3de95efc359483f810c4c3e6436279436',
  'symfony/stopwatch' => 'v4.4.25@80d9ae0c8a02bd291abf372764c0fc68cbd06c42',
  'symfony/yaml' => 'v4.4.26@e096ef4b4c4c9a2f72c2ac660f54352cd31c60f8',
  'theseer/fdomdocument' => '1.6.6@6e8203e40a32a9c770bcb62fe37e68b948da6dca',
  'theseer/tokenizer' => '1.2.0@75a63c33a8577608444246075ea0af0d052e452a',
  'vlucas/phpdotenv' => 'v2.6.7@b786088918a884258c9e3e27405c6a4cf2ee246e',
  'webmozart/assert' => '1.10.0@6964c76c7804814a842473e0c8fd15bab0f18e25',
  'magento/project-community-edition' => '2.3.5@',
);

    private function __construct()
    {
    }

    /**
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function rootPackageName() : string
    {
        if (!class_exists(InstalledVersions::class, false) || !(method_exists(InstalledVersions::class, 'getAllRawData') ? InstalledVersions::getAllRawData() : InstalledVersions::getRawData())) {
            return self::ROOT_PACKAGE_NAME;
        }

        return InstalledVersions::getRootPackage()['name'];
    }

    /**
     * @throws OutOfBoundsException If a version cannot be located.
     *
     * @psalm-param key-of<self::VERSIONS> $packageName
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function getVersion(string $packageName): string
    {
        if (class_exists(InstalledVersions::class, false) && (method_exists(InstalledVersions::class, 'getAllRawData') ? InstalledVersions::getAllRawData() : InstalledVersions::getRawData())) {
            return InstalledVersions::getPrettyVersion($packageName)
                . '@' . InstalledVersions::getReference($packageName);
        }

        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }

        throw new OutOfBoundsException(
            'Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files'
        );
    }
}
