<?php
namespace webQAdmin\controller;

use core\base\exceptions\RouteException;


class CreatesitemapController extends BaseAdmin
{

    protected $pagesCounter = 100;

    protected $all_links = [];
    protected $temp_links = [];
    protected $checked_links = [];

    protected $siteMapLinks = [];

    protected $maxLinks = 50;

    protected $minimalLinksCountInPage = 10;

    protected $dop = [];

    protected $parsingLogFile = 'parsing_log.txt';

    protected $fileArr = ['jpg', 'png', 'jpeg', 'gif', 'xls', 'xlsx', 'pdf', 'mp4', 'mpeg', 'mp3', 'zip', 'docx'];

    protected $filterArr = [
        'url' => ['/search/', '/sendmail/', '/cart/', '/lk/'],
        'get' => ['order', 'filters', 'price_min', 'price_max', 'store_id']
    ];

    public $siteUrl;

    protected $fileName = '';

    protected $sitesDir = '';

    protected $cookieDir = '';

    protected $parsingDir = '';

    protected $domain = '';

    protected $baseHref = '';

    protected $changeProtocol = 0;

    public function inputData($links_counter = 0, $redirect = true){

        parent::inputData();

        $this->sitesDir = $_SERVER['DOCUMENT_ROOT'] . PATH;

        $this->parsingDir = $_SERVER['DOCUMENT_ROOT'] . PATH . 'parsing/';

        $this->cookieDir = $_SERVER['DOCUMENT_ROOT'] . PATH . 'cookie/';

        $this->siteUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];

        preg_match('/(^https?:\/\/)?(www\.)?([^\/]+)/i', $this->siteUrl, $matches);

        if(empty($matches[0]) || empty($matches[3])){

            exit('Ошибка формирования домена сайта - ' . $this->sitesDir);

        }

        $startUrl = $this->siteUrl;

        $this->siteUrl = $matches[0];

        $this->domain = $matches[3];

        $this->fileName = $_SERVER['DOCUMENT_ROOT'] . PATH . 'log/' . $this->domain . '.txt';

        $links_counter = (int)$links_counter;

        if(!function_exists('curl_init')){

            $this->cancel(0, 'Library CURL as absent. Creation of sitemap imposible', '', true);

        }

        ini_set('mysql.connect_timeout', 200);

        set_time_limit(0);

        $reserve = json_decode(@file_get_contents($this->fileName), true);

        if($reserve){

            foreach ($reserve as $name => $item){

                $this->$name = $item;

            }

        }else{

            if($startUrl !== $this->siteUrl)
                $this->checked_links[] = $this->temp_links[] = $this->all_links[] = $this->siteUrl;

            $this->checked_links[] = $this->temp_links[] = $this->all_links[] = $startUrl;

        }

        $this->maxLinks = (int)$links_counter > 1 ? ceil($this->maxLinks / $links_counter) : $this->maxLinks;

        while ($this->temp_links){

            if($this->pagesCounter <= 0){

                $countLinks = count($this->all_links);

                return 'С домена ' . $this->siteUrl . ' собрано ' . $countLinks . ' ссылок';

            }

            $temp_links_count = count($this->temp_links);

            $links = $this->temp_links;

            $this->temp_links = [];

            if($temp_links_count > $this->maxLinks){

                $links = array_chunk($links, $this->maxLinks);

                $count_chunks = count($links);

                for($i = 0; $i < $count_chunks; $i++){

                    $this->parsing($links[$i]);

                    unset($links[$i]);

                    if($links){

                        file_put_contents($this->fileName, json_encode([
                            'all_links' => $this->all_links,
                            'temp_links' => array_merge(...$links),
                            'checked_links' => $this->checked_links
                        ]));

                    }

                }


            }else{

                $this->parsing($links);

            }

            file_put_contents($this->fileName, json_encode([
                'all_links' => $this->all_links,
                'temp_links' => $this->temp_links,
                'checked_links' => $this->checked_links
            ]));

        }


        file_put_contents($this->fileName, '');


        if($this->all_links){

            foreach ($this->all_links as $key => $link){

                if(!$this->filter($link)) unset($this->all_links[$key]);

            }

        }

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . PATH . 'log/dop.txt', json_encode($this->dop));

        $this->createSitemap();

        if($redirect){

            !$_SESSION['res']['answer'] && $_SESSION['res']['answer'] = '<div class="success">Sitemap is created</div>';

            $this->redirect();

        }else{

            return $this->cancel(1, 'Sitemap is created in domain' . $this->siteUrl . ' - ' . count($this->all_links) . ' links', '', true);

        }

    }

    protected function parsing($urls, $recurcive = false, $returnResult = false){

        if(!$urls) return false;

        $curlMulty = curl_multi_init();

        $curl = [];

        $timeOut = !$recurcive ? 10 : 50;

        foreach ($urls as $i => $url){

            $curl[$i] = curl_init();
            curl_setopt($curl[$i], CURLOPT_URL, $url);
            curl_setopt($curl[$i], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl[$i], CURLOPT_HEADER, true);
            curl_setopt($curl[$i], CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl[$i], CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl[$i], CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl[$i], CURLOPT_CONNECTTIMEOUT, $timeOut);
            curl_setopt($curl[$i], CURLOPT_TIMEOUT, $timeOut * 2);
            curl_setopt($curl[$i], CURLOPT_ENCODING, 'gzip,deflate');
            curl_setopt ($curl[$i], CURLOPT_COOKIEFILE, $this->cookieDir . 'cookie.txt');
            curl_setopt($curl[$i], CURLOPT_COOKIEJAR, $this->cookieDir . 'cookie.txt');

            curl_multi_add_handle($curlMulty, $curl[$i]);

        }

        do{

            $status = curl_multi_exec($curlMulty, $active);
            $info = curl_multi_info_read($curlMulty);

            if(false !== $info){

                if($info['result'] !== 0){

                    $i = array_search($info['handle'], $curl);

                    $error = curl_errno($curl[$i]);
                    $message = curl_error($curl[$i]);
                    $header = curl_getinfo($curl[$i]);

                    if($error != 0){

                        $this->cancel(0, 'Error loading ' . $header['url'] .
                            ' http code: ' . $header['http_code'] .
                            ' error: ' . $error . ' message' . $message
                        );

                    }

                }

            }

            if($status > 0){

                $this->cancel(0, curl_multi_strerror($status));

            }


        }while($status === CURLM_CALL_MULTI_PERFORM || $active);


        $result = [];

        $badLinks = [];

        foreach($urls as $i => $url){

            if(!$this->changeProtocol){

                $infoUrl = curl_getinfo($curl[$i], CURLINFO_EFFECTIVE_URL);

                if($infoUrl && preg_match('/^\s*(https?)/', $infoUrl, $matches)){

                    $this->siteUrl = preg_replace('/^https?/', $matches[1], $this->siteUrl);

                    if($this->all_links){

                        foreach ($this->all_links as $key => $item){

                            $this->all_links[$key] = preg_replace('/^https?/', $matches[1], $item);

                        }

                    }

                    if($this->temp_links){

                        foreach ($this->temp_links as $key => $item){

                            $this->temp_links[$key] = preg_replace('/^https?/', $matches[1], $item);

                        }

                    }

                    $this->changeProtocol = 1;

                }

            }


            $result[$i] = curl_multi_getcontent($curl[$i]);
            curl_multi_remove_handle($curlMulty, $curl[$i]);
            curl_close($curl[$i]);

            if(!preg_match('/HTTP\/\d\.?\d?\s+20\d/i', $result[$i])){

                $badLinks[] = $url;

                $this->cancel(0, 'Incorrect server code ' . $url);

                continue;

            }

            if(!preg_match('/Content-Type:\s+text\/html/i', $result[$i]) &&
                !preg_match('/^.*?((<\s*!\s*doctype[^>]+>)|(<\s*html[^>]*>)|(<\s*body[^>]*>))/is', $result[$i])){

                $badLinks[] = $url;

                $this->cancel(0, 'Incorrect content type ' . $url);

                continue;

            }

            if($recurcive){

                $this->cancel(0, 'CORRECT ANSWER FROM URL ' . $url);

            }

            if(!$returnResult){

                $this->createLinks($result[$i], $url);

            }

        }

        curl_multi_close($curlMulty);

        if($badLinks && !$recurcive){

            $this->parsing($badLinks, true, $returnResult);

        }

        if($returnResult) return $result;

    }

    protected function createLinks($content, $url){

        if($content){

            $badContentFlag = false;

            preg_match_all('/<a\s*?[^>]*?href\s*?=(["\'])(.*?)\1[^>]*?>/is', $content, $links);

            if(empty($links[2]) || count($links[2]) < $this->minimalLinksCountInPage){

                $badContentFlag = true;

                $this->cancel(0, 'Bad content in url ' . $url);

                $res = $this->parsing([$url], false, true);

                if($res){

                    preg_match_all('/<a\s*?[^>]*?href\s*?=(["\'])(.*?)\1[^>]*?>/ui', $res[0], $links);

                }

            }

            if(!empty($links[2])){



                $badContentFlag && $this->cancel(0, 'RESTORE CONTENT IN URL ' . $url);

                foreach ($links[2] as $link){

                    $link = trim($link);

                    if(!$link || $link === '/' || $link === $this->siteUrl . '/') continue;

                    foreach ($this->fileArr as $ext){

                        if($ext){

                            $ext = addslashes($ext);
                            $ext = str_replace('.', '\.', $ext);

                            if(preg_match('/' . $ext . '(\s*?$|\?[^\/]*$)/ui', $link)){

                                continue 2;

                            }

                        }

                    }

                    $link = $this->createRelativeUrl($link, $url, $content);

                    $link && $link = preg_replace('/#[\w-]*/', '', $link);

                    if(!$link || !$this->filter($link) || strpos($link, '/') === false) continue;

                    $link = $this->siteUrl . $link;

                    if(!in_array($link, $this->all_links)){

                        $this->temp_links[] = $link;
                        $this->all_links[] = $link;

                    }

                }

            }

        }

    }

    protected function filter($link){

        if($this->filterArr){

            foreach ($this->filterArr as $type => $values){

                if($values){

                    foreach ($values as $item){

                        $item = str_replace('/', '\/', addslashes($item));

                        if($type === 'url'){
                            if(preg_match('/^[^?]*' . $item . '/ui', $link)){
                                return false;
                            }


                        }

                        if($type === 'get'){

                            if(preg_match('/(\?|&amp;|=|&)'. $item .'(=|&amp;|&|$)/ui', $link, $matches)){
                                return false;
                            }


                        }

                    }

                }

            }

        }

        return true;

    }

    protected function cancel($success = 0, $message = '', $log_message = '', $exit = false){

        $exitArr = [];

        $exitArr['success'] = $success;
        $exitArr['message'] = $message ? $message : 'ERROR PARSING';
        $log_message = $log_message ? $log_message : $exitArr['message'];

        $class = 'success';

        if(!$exitArr['success']){

            $class = 'error';

            $this->writeLog($log_message, $this->parsingLogFile);

        }else{

            $exitArr['message'] = '<div> class="' .$class. '">' . $exitArr['message'] . '</div>';

        }

        if($exit){

            exit(json_encode($exitArr));
        }

        return $exitArr['message'];

    }

    protected function createSitemap(){

        $dom = new \domDocument('1.0', 'utf-8');
        $dom->formatOutput = true;

        $root = $dom->createElement('urlset');
        $root->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        //$root->setAttribute('xmlns:xls', 'http://w3.org/2001/XMLSchema-instance');
        //$root->setAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

        $dom->appendChild($root);

        $sxe = simplexml_import_dom($dom);

        if($this->all_links){

            $date = new \DateTime();
            $lastMod = $date->format('Y-m-d') . 'T' . $date->format('H:i:s+01:00');

            foreach($this->all_links as $item){

                $elem = trim(mb_substr($item, mb_strlen($this->siteUrl)), '/');
                $elem = explode('/', $elem);

                $count = '0.' . (count($elem) - 1);
                $priority = 1 - (float)$count;

                if($priority == 1) $priority = '1.0';

                $urlMain = $sxe->addChild('url');

                $urlMain->addChild('loc', htmlspecialchars($item));

                $urlMain->addChild('lastmod', $lastMod);
                $urlMain->addChild('changefreq', 'weekly');
                $urlMain->addChild('priority', $priority);

            }

        }

        $dom->save($this->sitesDir . 'sitemap.xml');

    }

    protected function createRelativeUrl($url, $baseUrl = '', $content = '', $endSlash = false){

        $url = trim($url);

        $realEndSlash = '';

        if(!$baseUrl && !$content){

            $baseHref = $this->baseHref;

        }else{

            if(preg_match('/<\s*base\s+.*?href\s*=\s*(["\'])(.+?)\1/i', $content, $matches)){

                $baseHref = trim(preg_replace('/^\s*(https?:\/\/)?([^\/]+)/', '', $matches[2]));

                !$baseHref && $baseHref = '/';

            }else{

                $baseHref = preg_replace('/^(https?:\/\/)?[^\/]+/i', '', $baseUrl);

                if($baseHref){

                    $arr = preg_split('/\s*\/\s*/', $baseHref, 0, PREG_SPLIT_NO_EMPTY);

                    if($arr){

                        unset($arr[count($arr) - 1]);

                        $baseHref = $arr ? '/' . implode('/', $arr) . '/' : '/';

                    }

                }else{

                    $baseHref = '/';

                }

            }

        }

        if(!preg_match('/^\s*\//i', $url)){

            if(preg_match('/\/\s*$/', $url)) $realEndSlash = '/';

            $domain = preg_quote($this->domain);

            if(preg_match('/' . $domain . '/i', $url) && !preg_match('/^mailto\s*:/i', $url)){

                $url = trim(preg_replace('/^.*?' . $domain . '(.*)/', '$1', $url));

                if(!$url) $url = '/';

            }elseif (!$url || preg_match('/^((https?:)|(mailto\s*:)|(tel\s*:))/i', $url, $mtest) ||
                preg_match('/' . str_replace('/', '\/', preg_quote($url)) . '\/?$/', $baseUrl, $matches)){

                if(!empty($matches)){

                    $this->cancel(0, '', 'Дубликат относительной ссылки - ' . $url . ' на странице - ' . $baseUrl);

                }
                return false;

            }elseif ($baseHref && strpos($url, '#') !== 0){

                $baseArr = preg_split('/\s*\/\s*/', $baseHref, 0, PREG_SPLIT_NO_EMPTY);

                $urlArr = preg_split('/\s*\/\s*/', $url, 0, PREG_SPLIT_NO_EMPTY);

                $countBaseArr = count($baseArr);

                foreach ($urlArr as $key => $item){

                    if($item === '.'){

                        unset($urlArr[$key]);

                    }elseif ($item === '..'){

                        unset($baseArr[$countBaseArr - ($key + 1)], $urlArr[$key]);

                    }

                }

                $baseUrl = implode('/', $baseArr);

                $url = implode('/', $urlArr);

                $url = $baseUrl ? '/' . $baseUrl . '/' . $url : '/' . $url;

            }

        }

        $url .= $realEndSlash;

        if($endSlash){

            $url = preg_split('/\?/', $url, 0, PREG_SPLIT_NO_EMPTY);

            if(!preg_match('/#[^\/]*$/', $url[0])) $url[0] .= '/';

            $url = '/' . implode('?', $url);

        }

        return trim(preg_replace('/\/{2,}/', '/', $url));

    }

}