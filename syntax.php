  <?php
         
    // must be run within DokuWiki
    if(!defined('DOKU_INC')) die();
     
    if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
    require_once(DOKU_PLUGIN.'syntax.php');
     
    /**
     * All DokuWiki plugins to extend the parser/rendering mechanism
     * need to inherit from this class
     */
    class syntax_plugin_nodisp extends DokuWiki_Syntax_Plugin {
     
        /**
         * return some info
         */
        function getInfo(){
            return array(
                'author' => 'Myron Turner',
                'email'  => 'turnermm02 AT shaw DOT ca',
                'date'   => '2010-03-19',
                'name'   => 'nodisp Plugin',
                'desc'   => 'hides display of enclosed text',
                'url'    => 'http://www.mturner.org',
            );
        }
     
        function getType(){ return 'formatting'; }
        function getAllowedTypes() { return array('formatting', 'substition', 'disabled'); }   
        function getSort(){ return 168; }
        function connectTo($mode) { $this->Lexer->addEntryPattern('<nodisp.*?>(?=.*?</nodisp>)',$mode,'plugin_nodisp'); }
        function postConnect() { $this->Lexer->addExitPattern('</nodisp>','plugin_nodisp'); }
     
     
        /**
         * Handle the match
         */
        function handle($match, $state, $pos, &$handler){


            switch ($state) {
              case DOKU_LEXER_ENTER : 
               return array($state, false);
     
              case DOKU_LEXER_UNMATCHED :  return array($state, $match);
              case DOKU_LEXER_EXIT :       return array($state, '');
            }
       
            return array();
        }
     
        /**
         * Create output
         */
        function render($mode, &$renderer, $data) {
            global $INFO;
            if($mode == 'xhtml'){
                list($state, $match) = $data;
                switch ($state) {
                  case DOKU_LEXER_ENTER :  
                    if($INFO['isadmin'] || $INFO['ismanager'] ) break;   
                    $renderer->doc .= "<span style='display:none'>"; 
                    break;
     
                  case DOKU_LEXER_UNMATCHED :  $renderer->doc .= $renderer->_xmlEntities($match); break;
                  case DOKU_LEXER_EXIT : 
                    if($INFO['isadmin'] || $INFO['ismanager'] ) break;   
                    $renderer->doc .= "</span>"; break;
                }
                return true;
            }
            return false;
        }
     


}