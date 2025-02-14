<?php


namespace App\Services;


class MKMService
{

    private $baseUrl = "https://api.cardmarket.com/ws/v2.0/output.json/";
    //private $baseUrl = "https://sandbox.cardmarket.com/ws/v2.0/output.json/";
    //private $baseUrl = "https://sandbox.mkmapi.eu/ws/v2.0/output.json/";
    private $method;
    private $url;
    private $appToken;
    private $appSecret;
    private $accessToken;
    private $nonce;
    private $timestamp;
    private $signatureMethod;
    private $version;

    private $params;
    private $baseString;
    private $header;

    private $languages = array('cz', 'EN', 'FR', 'DE', 'ES', 'IT', 'CH', 'JA', 'PO', 'RU', 'KO', 'TCH');

    /*
     *    1 - English
     *    2 - French
     *    3 - German
     *    4 - Spanish
     *    5 - Italian
     *    6 - Simplified Chinese
     *    7 - Japanese
     *    8 - Portuguese
     *    9 - Russian
     *    10 - Korean
     *    11 - Traditional Chinese
     */

    public function getGames()
    {
        return $this->call("games");
    }

    public function getAccount()
    {
        return $this->call("account");
    }

    public function getExpansions($idGame = 1)
    {
        return $this->call("games/" . $idGame . "/expansions");
    }

    public function getSingles($idExpansion = 2447)
    {
        return $this->call("expansions/" . $idExpansion . "/singles");
    }

    public function getProduct($idProduct = 378099)
    {
        return $this->call("products/" . $idProduct);
    }

    public function getArticle($idArticle)
    {
        return $this->call("stock/article/". $idArticle);
    }

    public function getStock()
    {
        return $this->call("stock");
    }

    public function getPriceGuide()
    {
        return $this->call("priceguide");
    }

//bought or 1
//paid or 2
//sent or 4
//received or 8
//lost or 32
//cancelled or 128
    public function getSellerOrders($state){
        return $this->call("orders/1/" . $state);
    }

    public function getProductList()
    {
        return $this->call("productlist");
    }

    public function addToStock($idProduct, $count, $price, $condition = "MT", $language = "EN", $comments = "", $isFoil = "false", $isSigned = "false", $isAltered = "false", $isPlayset = "false")
    {
        $idLanguage = array_search(strtoupper($language), $this->languages);
        \Debugbar::info($idLanguage);
        $data = new product();

        $data->idProduct = $idProduct;
        $data->idLanguage = $idLanguage;
        $data->comments = $comments;
        $data->count = $count;
        $data->price = $price;
        $data->condition = $condition;
        $data->isFoil = $isFoil;
        $data->isSigned = $isSigned;
        $data->isAltered = $isAltered;
        $data->isPlayset = $isPlayset;

        return $this->call("stock", "POST", $data);
    }

    public function increaseStock($idArticle, $quantity)
    {
        $data = new baseArticle();
        $data->idArticle = $idArticle;
        $data->count = $quantity;
        return $this->call("stock/increase", "PUT", $data);
    }

    public function decreaseStock($idArticle, $quantity)
    {
        $data = new baseArticle();
        $data->idArticle = $idArticle;
        $data->count = $quantity;
        return $this->call("stock/decrease", "PUT", $data);
    }

    public function changeArticleInStock($idArticle, $count, $price, $condition = "MT", $language = "EN", $comments = "", $isFoil = "false", $isSigned = "false", $isAltered = "false", $isPlayset = "false")
    {
        $idLanguage = array_search(strtoupper($language), $this->languages);

        $data = new article();

        $data->idArticle = $idArticle;
        $data->idLanguage = $idLanguage;
        $data->comments = $comments;
        $data->count = $count;
        $data->price = $price;
        $data->condition = $condition;
        $data->isFoil = $isFoil;
        $data->isSigned = $isSigned;
        $data->isAltered = $isAltered;
        $data->isPlayset = $isPlayset;
        return $this->call("stock", "PUT", $data);
    }

    public function deleteFromStock($idArticle, $count)
    {
        $data = new baseArticle();

        $data->idArticle = $idArticle;
        $data->count = $count;

        return $this->call("stock", "DELETE", $data);

    }

    private function call($command, $method = "GET", $data = null)
    {
        $this->init($command, $method);
        $this->setParams();
        $this->setMethodAndUrl();
        $this->encodeParams();
        $this->sign();
        $this->getHeader();
        $xmlData = null;

        if ($data != null)

            $xmlData = $data->getXml();

        //\Debugbar::info($xmlData);

        return $this->exec($xmlData);
    }


    /**
     * Declare and assign all needed variables for the request and the header
     *
     * @var $method string Request method
     * @var $url string Full request URI
     * @var $appToken string App token found at the profile page
     * @var $appSecret string App secret found at the profile page
     * @var $accessToken string Access token found at the profile page (or retrieved from the /access request)
     * @var $accessSecret string Access token secret found at the profile page (or retrieved from the /access request)
     * @var $nonce string Custom made unique string, you can use uniqid() for this
     * @var $timestamp string Actual UNIX time stamp, you can use time() for this
     * @var $signatureMethod string Cryptographic hash function used for signing the base string with the signature, always HMAC-SHA1
     * @var version string OAuth version, currently 1.0
     */
    private function init($command, $method)
    {
        $url = $this->baseUrl . $command;
        $this->method = $method;
        $this->url = $url;
        $this->appToken = "pOvuvylU7zLW8wW0";
        $this->appSecret = "SmKuTvyuSOHeTblc9IWekFZ4q4CzPGAG";
        $this->accessToken = "vRj39mHudtGfKCaKF6bXwIoL2XCKWTVx";
        $this->accessSecret = "QPbBWajSgTfaINwtka9P5EPpsSrQ7X89";
        $this->nonce = $this->getNonce();
        $this->timestamp = time();
        $this->signatureMethod = "HMAC-SHA1";
        $this->version = "1.0";


    }

    /**
     * Gather all parameters that need to be included in the Authorization header and are know yet
     *
     * Attention: If you have query parameters, they MUST also be part of this array!
     *
     * @var $params array|string[] Associative array of all needed authorization header parameters
     */
    private function setParams()
    {
        $this->params = array(
            'realm' => $this->url,
            'oauth_consumer_key' => $this->appToken,
            'oauth_token' => $this->accessToken,
            'oauth_nonce' => $this->nonce,
            'oauth_timestamp' => $this->timestamp,
            'oauth_signature_method' => $this->signatureMethod,
            'oauth_version' => $this->version,
        );
        //\Debugbar::info($this->params);
    }

    /**
     * Start composing the base string from the method and request URI
     *
     * Attention: If you have query parameters, don't include them in the URI
     *
     * @var $baseString string Finally the encoded base string for that request, that needs to be signed
     */
    private function setMethodAndUrl()
    {
//check if query parameters
        $url = $this->url;
        $this->baseString = strtoupper($this->method) . "&";
        $this->baseString .= rawurlencode($url) . "&";

    }

    /*
     * Gather, encode, and sort the base string parameters
     */
    private function encodeParams()
    {
        $encodedParams = array();
        foreach ($this->params as $key => $value) {
            if ("realm" != $key) {
                $encodedParams[rawurlencode($key)] = rawurlencode($value);
            }
        }
        ksort($encodedParams);
        /*
    * Expand the base string by the encoded parameter=value pairs
    */
        $values = array();
        foreach ($encodedParams as $key => $value) {
            $values[] = $key . "=" . $value;
        }
        $paramsString = rawurlencode(implode("&", $values));
        $this->baseString .= $paramsString;
        //    \Debugbar::info($this->baseString);

    }

    private function sign()
    {

        /*
         * Create the signingKey
         */
        $signatureKey = rawurlencode($this->appSecret) . "&" . rawurlencode($this->accessSecret);

        /**
         * Create the OAuth signature
         * Attention: Make sure to provide the binary data to the Base64 encoder
         *
         * @var $oAuthSignature string OAuth signature value
         */
        $rawSignature = hash_hmac("sha1", $this->baseString, $signatureKey, true);
        $oAuthSignature = base64_encode($rawSignature);

        /*
         * Include the OAuth signature parameter in the header parameters array
         */
        $this->params['oauth_signature'] = $oAuthSignature;

    }

    private function getHeader()
    {

        /*
         * Construct the header string
         */
        $this->header = "Authorization: OAuth ";
        $headerParams = array();
        foreach ($this->params as $key => $value) {
            $headerParams[] = $key . "=\"" . $value . "\"";
        }
        $this->header .= implode(", ", $headerParams);
        //\Debugbar::info($this->header);
    }

    private function exec($data = null)
    {

        /*
         * Get the cURL handler from the library function
         */
        $curlHandle = curl_init();

        /*
         * Set the required cURL options to successfully fire a request to MKM's API
         *
         * For more information about cURL options refer to PHP's cURL manual:
         * http://php.net/manual/en/function.curl-setopt.php
         */
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_URL, $this->url);
        //\Debugbar::info($data);
        if ($data != null) {
            curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, $this->method);
            curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array($this->header));
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);

        /**
         * Execute the request, retrieve information about the request and response, and close the connection
         *
         * @var $content string Response to the request
         * @var $info array Array with information about the last request on the $curlHandle
         */
        $content = curl_exec($curlHandle);
        $info = curl_getinfo($curlHandle);
        curl_close($curlHandle);
        //\Debugbar::info($info);

        /*
         * Convert the response string into an object
         *
         * If you have chosen XML as response format (which is standard) use simplexml_load_string
         * If you have chosen JSON as response format use json_decode
         *
         * @var $decoded \SimpleXMLElement|\stdClass Converted Object (XML|JSON)
         */
        return json_decode($content);
        //return simplexml_load_string($content);
    }

    private function getNonce()
    {
        return rand(1000000000000, 9999999999999);
    }

}


class baseArticle
{
    public $idArticle;
    public $count;

    public function getXML()
    {

        $s = '<?xml version="1.0" encoding="UTF-8" ?>
<request>
    <article>
        <idArticle>' . $this->idArticle . '</idArticle>
        <count>' . $this->count . '</count>
    </article>
</request>';
        //echo $s;
        return $s;
    }
}

class article extends baseArticle
{
    public $idLanguage = "1";
    public $comments = "";
    public $price;
    public $condition = "MT";
    public $isFoil = "false";
    public $isSigned = "false";
    public $isAltered = "false";
    public $isPlayset = "false";

    public function getXML()
    {

        return '<?xml version="1.0" encoding="UTF-8" ?>
<request>
    <article>
        <idArticle>' . $this->idArticle . '</idArticle>
        <idLanguage>' . $this->idLanguage . '</idLanguage>
        <comments>' . $this->comments . '</comments>
        <count>' . $this->count . '</count>
        <price>' . $this->price . '</price>
        <condition>' . $this->condition . '</condition>
        <isFoil>' . $this->isFoil . '</isFoil>
        <isSigned>' . $this->isSigned . '</isSigned>
        <isAltered>' . $this->isAltered . '</isAltered>
        <isPlayset>' . $this->isPlayset . '</isPlayset>
    </article>
</request>';
    }
}

class product extends article
{
    public $idProduct;

    public function getXML()
    {
        return '<?xml version="1.0" encoding="UTF-8" ?>
<request>
    <article>
        <idProduct>' . $this->idProduct . '</idProduct>
        <idLanguage>' . $this->idLanguage . '</idLanguage>
        <comments>' . $this->comments . '</comments>
        <count>' . $this->count . '</count>
        <price>' . $this->price . '</price>
        <condition>' . $this->condition . '</condition>
        <isFoil>' . $this->isFoil . '</isFoil>
        <isSigned>' . $this->isSigned . '</isSigned>
        <isAltered>' . $this->isAltered . '</isAltered>
        <isPlayset>' . $this->isPlayset . '</isPlayset>
    </article>
</request>';
    }
}
