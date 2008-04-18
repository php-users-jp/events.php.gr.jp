<?php
/**
 * Parser
 *
 * @author halt <halt.hde@gmail.com>
 */

require_once 'NP_HatenaLike.php';

/**
 * Parser
 *
 * @author halt <halt.hde@gmail.com>
 *
 */
class Parser
{

    /**
     * Parser List
     * @var     array
     * @access  protected
     */
    var $parser = array(
        'anubis',
        'tdiary',
        'html',
        'kinowiki'
        );

    //{{{ getParserList
    /**
     * getParserList
     *
     * @return array
     */
    function getParserList()
    {
        return $this->parser;
    }
    //}}}
    
    //{{{ parseTDiary()
    /**
     * parseTDiary()
     *
     */
    function parseTDiary($str)
    {
        $output = "";
        $lines = explode("\n", $str);
        foreach ( $lines as $value) {
            $value   = trim($value);
            $buf = strip_tags($value);
            if (!empty($buf)) {
                $output .= "<p>{$value}</p>\n";
            } else {
                $output .= "{$value}\n";
            }
        }

        return $output;
    }
    //}}}

    //{{{ parseAnubis()
    /**
     * parseAnubis()
     *
     */
    function parseAnubis($str)
    {
        $nest     = 0;
        $lines    = explode("\n", $str);
        $TAGS     = "(h[1-6]|p|ul|ol|dl|blockquote|address|pre|table|div)";
        $res      = "";
        $divs     = FALSE;
        for($i = 0; $i < count($lines); $i++)
        {
            if(!preg_match("/^\s*$/", $lines[$i])) {
                
                //ブロックタグ 開始          
                if(preg_match("/<({$TAGS}).*?>/", $lines[$i], $result)) {
                    $nest++;          
                }

                //divを開始する？ divの外であり、h\d行・hr以外なら開始       
                if(!$divs && !preg_match("/<h[r1-6]>/", $lines[$i]))         
                {        
                    $res .= "\n<div class=\"subsection\">\n";        
                    $divs = TRUE;        
                }        
                         
                //行挿入 ブロックのネスト中・hrならそのまま、それ以外なら段落にして          
                if($nest || preg_match("/<hr.*?>/", $lines[$i])) {
                    $res .= "$lines[$i]\n";
                } else {
                    $res .= "<p>$lines[$i]</p>\n";
                }

                //divを終了する？ divの中であり、次の行が空行・h\d行・hrのいずれかなら終了       
                if($divs && !isset($lines[$i + 1]) || preg_match("/(?:<h[r1-6]>|^\s*$)/", $lines[$i + 1]))         
                {
                    if ($nest == 0) {
                    $res .= "</div>\n";
                    $divs = FALSE;
                    }
                } 
                         
                //ブロックタグ 終了          
                if(preg_match("/<\/$TAGS.*?>/", $lines[$i])) {        
                    $nest--;         
                }            
            }        
        }        
             
        return $res;
    }
    //}}}
    
}
