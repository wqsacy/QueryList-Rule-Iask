<?php

	namespace QL\Ext;

	use QL\Contracts\PluginContract;
	use QL\QueryList;

	/**
	 *  QueryList Rule Iask
	 * Created by Malcolm.
	 * Date: 2021/4/25  15:59
	 */
	class Iask implements PluginContract
	{
		protected $ql;
		protected $keyword;
		protected $pageNumber = 10;
		protected $httpOpt = [
			'headers' => [
				'User-Agent'      => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36' ,
				'Accept-Encoding' => 'gzip, deflate, br' ,
			]
		];
		const API = 'https://iask.sina.com.cn/search';
		const RULES = [
			'title' => [ '.title-text' , 'text' ] ,
			'link'  => [ '.title-text a' , 'href' ]
		];
		const RANGE = '.iask-search-list li';

		public function __construct ( QueryList $ql , $pageNumber ) {
			$this->ql = $ql->rules( self::RULES )
			               ->range( self::RANGE );
			$this->pageNumber = $pageNumber;
		}

		public static function install ( QueryList $queryList , ...$opt ) {
			$name = $opt[0] ?? 'iask';
			$queryList->bind( $name , function ( $pageNumber = 10 )
			{
				return new Iask( $this , $pageNumber );
			} );
		}

		public function setHttpOpt ( array $httpOpt = [] ) {
			if ( count( $httpOpt ) )
				$this->httpOpt = array_merge( $this->httpOpt , $httpOpt );
			return $this;
		}

		public function search ( $keyword ) {
			$this->keyword = $keyword;

			$encode = urlencode( $keyword );

			$this->httpOpt['headers']['referer'] = "https://iask.sina.com.cn/search?searchWord={$encode}&record=1";

			return $this;
		}

		public function page ( $page = 1 , $realURL = false ) {
			return $this->query( $page )
			            ->query()
			            ->getData( function ( $item ) use ( $realURL )
			            {
				            return $item;
			            } );
		}

		public function getCount () {
			$count = 0;
			$text = $this->query( 1 )
			             ->find( '.search-result' )
			             ->text();
			if ( preg_match( '/[\d,]+/' , $text , $arr ) ) {
				$count = str_replace( ',' , '' , $arr[0] );
			}
			return (int) $count;
		}

		public function getCountPage () {
			$count = $this->getCount();
			$countPage = ceil( $count / $this->pageNumber );
			return $countPage;
		}

		protected function query ( $page = 1 ) {
			$this->ql->get( self::API , [
				'searchWord' => $this->keyword ,
				'page'       => $page
			] , $this->httpOpt );
			return $this->ql;
		}

	}