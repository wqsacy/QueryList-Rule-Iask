# QueryList-Rule-Baidu

QueryList Plugin: Iask searcher.

QueryList插件：新浪爱问搜索引擎

> QueryList:[https://github.com/wqsacy/QueryList-Rule-Iask.git](https://github.com/wqsacy/QueryList-Rule-Iask)

## Installation for QueryList4

```
composer require wangqs/querylist-rule-iask
```

## API

- Iask **iask($pageNumber = 10)**:get Iask Searcher.

class **Iask**:

- Iask **search($keyword)**:set search keyword.
- Iask **setHttpOpt(array $httpOpt = [])**：Set the http
  option,see: [GuzzleHttp options](http://docs.guzzlephp.org/en/stable/request-options.html)
- int **getCount()**:Get the total number of search results.
- int **getCountPage()**:Get the total number of pages.
- Collection **page($page = 1,$realURL = false)**:Get search results

## Usage

- Installation Plugin

```php
use QL\QueryList;
use QL\Ext\Baidu;

$ql = QueryList::getInstance();
$ql->use(Iask::class);
//or Custom function name
$ql->use(Iask::class,'iask');
```

- Example-1

```php
$baidu = $ql->iask(10);
$searcher = $baidu->search('什么是快乐星球');
$count = $searcher->getCount();
$data = $searcher->page(1);
$data = $searcher->page(2);

$searcher = $baidu->search('什么是快乐星球');
$countPage = $searcher->getCountPage();
for ($page = 1; $page <= $countPage; $page++)
{
    $data = $searcher->page($page);
}
```

- Example-2

```php
$searcher = $ql->baidu()->search('什么是快乐星球');
$data = $searcher->setHttpOpt([
    // Set the http proxy
    'proxy' => 'http://222.141.11.17:8118',
   // Set the timeout time in seconds
    'timeout' => 30,
])->page(1);
```

- Example-3

```php
$baidu = $ql->baidu(3)
$searcher = $baidu->search('什么是快乐星球');

$data = $searcher->page(1);
print_r($data->all());

// Get real url
$data = $searcher->page(1,true);
print_r($data->all());
```

Out:

```
Array
(
    [0] => Array
        (
            [title] => 快乐星球
            [link] => https://iask.sina.com.cn/b/3602430.html
        )
    [1] => Array
        (
            [title] => 哪里能看快乐星球
            [link] => https://iask.sina.com.cn/b/8520162.html
        )
    [2] => Array
        (
            [title] => 快乐星球
            [link] => https://iask.sina.com.cn/b/5887903.html
        )

)

Array
(
    [0] => Array
        (
            [title] => 快乐星球
            [link] => https://iask.sina.com.cn/b/3602430.html
        )
    [1] => Array
        (
            [title] => 哪里能看快乐星球
            [link] => https://iask.sina.com.cn/b/8520162.html
        )
    [2] => Array
        (
            [title] => 快乐星球
            [link] => https://iask.sina.com.cn/b/5887903.html
        )
)

```
