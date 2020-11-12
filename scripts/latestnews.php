<?php

class NewsAuth{
    protected $key;
    public function __construct(string $key=null)
    {
        $this->key = $key;
    }

    /**
     * @param string|null $key
     */
    public function setKey(?string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    public function __invoke()
    {
        return $this->getKey();
    }
}
class NewsSearch{
    protected $domain,$term, $time, $sort;
    public function __construct(string $term=null, string $domain = null, DateTimeImmutable $time=null, string $sort=null)
    {
        $this->term = $term ?? "everything";
        $this->domain = $domain ?? "everything";
        $this->time = $time ?? new DateTimeImmutable();
        $this->sort = $sort ?? "publishedAt";
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     * @return NewsSearch
     */
    public function setDomain(string $domain): NewsSearch
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTerm(): ?string
    {
        return $this->term;
    }

    /**
     * @param string|null $term
     * @return NewsSearch
     */
    public function setTerm(?string $term): NewsSearch
    {
        $this->term = $term;
        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getTime(): DateTimeImmutable
    {
        return $this->time;
    }

    /**
     * @param DateTimeImmutable $time
     * @return NewsSearch
     */
    public function setTime(DateTimeImmutable $time): NewsSearch
    {
        $this->time = $time;
        return $this;
    }

    /**
     * @return string
     */
    public function getSort(): string
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     * @return NewsSearch
     */
    public function setSort(string $sort): NewsSearch
    {
        $this->sort = $sort;
        return $this;
    }
}
class NewsSearchResult{
    protected $status, $items;

    public function __construct(bool $status, array $items = [])
    {
        $this->status = $status;
        $this->items = $this->filterItems($items);;
    }

    private function filterItems(array $items){
        $filteredItems = [];
        foreach ($items as $item) {

            if (is_array($item)) {
                $item = new NewsItem(
                    $item["title"],
                    $item["author"],
                    $item["source"]["name"] ?? "",
                    $item["content"],
                    $item["description"],
                    $item["url"],
                    $item["urlToImage"],
                    $item["publishedAt"]
                );
            }

            if ( $item instanceof NewsItem ){
                $hash = md5($item->getDescription());
                if ( !isset( $filteredItems[$hash] ) ){
                    $filteredItems[$hash] = $item;
                }
            }

        }
        return $filteredItems;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getStatus():bool
    {
        return $this->status;
    }
}
class NewsItem{
    protected $title, $author, $source, $content, $description, $url, $image, $publishedAt;

    public function __construct(?string $title, ?string $author, ?string $source, ?string $content,
                                ?string $description, ?string $url, ?string $image, ?string $publishedAt){
        $this->title = $title ?? "";
        $this->author = $author ?? "";
        $this->source = $source ?? ""; # TODO: NewsItemSource::class
        $this->content = $content ?? "";
        $this->description = $description ?? "";
        $this->url = $url ?? "";
        $this->image = $image ?? "";
        $this->publishedAt = $publishedAt ? new \DateTimeImmutable($publishedAt) : null;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    public function getPublishedAt(): ?DateTimeImmutable
    {
        return $this->publishedAt;
    }
}
class News{
    protected $auth, $search;

    public function __construct(NewsAuth $auth, NewsSearch $search)
    {
        $this->auth = $auth;
        $this->search = $search;
    }

    public function getSearchResult()
    {
        $url = sprintf("https://newsapi.org/v2/%s?q=%s&apiKey=%s",
            $this->search->getDomain(),
            $this->search->getTerm(),
            $this->auth->getKey()
        );
        $c = curl_init($url);
        curl_setopt($c,CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($c);
        if ( $response ) {
            $result = json_decode($response, true);
            $status = $result["status"] ?? null;
            if ( $result && $status === "ok" ) {
                return new NewsSearchResult(
                    true,
                    $result["articles"]
                );
            }
        }
        return new NewsSearchResult(false);
    }
}
$key = $argv[1];

if ( !$key ){
    die("Please provide a key..!");
}

$term = $argv[2] ?? "everything";
$speed = $argv[3] ?? 6;

$auth = new NewsAuth($key);
$search = new NewsSearch($term);
$results = (new News($auth, $search))->getSearchResult()->getItems();

$header = "/!\\ News Ticker: {$search->getTerm()} /!\\";
$sep = str_repeat("=", strlen($header));

echo $sep . PHP_EOL;
echo $header . PHP_EOL;
echo $sep . PHP_EOL;

/** @var NewsItem $result */
foreach ($results as $result) {
    echo "[" . $result->getPublishedAt()->format("Y-m-d H:i") . "] ({$result->getSource()}) " . ($result->getDescription()) . PHP_EOL;
    echo $result->getUrl() . PHP_EOL;
    echo PHP_EOL;
    sleep($speed);
}
