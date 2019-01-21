<?php

class timer
{
    var $timers = array();
    
    function getmicrotime()
    { 
        list($usec, $sec) = explode(" ",microtime()); 
        return ((float)$usec + (float)$sec); 
    } 

    function start($name)
    { $this->timers[$name]['start'] = $this->getmicrotime(); }
    
    function end($name)
    { 
        if(!isset($this->timers[$name]['start']))
        { $this->start($name); }
        
        $this->timers[$name]['end'] = $this->getmicrotime(); 
        $this->timers[$name]['total'] = $this->timers[$name]['end'] - $this->timers[$name]['start'];
    }
    
    function gettime($name)
    { 
        if(!isset($this->timers[$name]['end']))
        { $this->end($name); }
        
        return $this->timers[$name]['total']; }
    
    function results($name = '')
    {
        $retval = '';
        
        if($name != '')
        { 
            if(!isset($this->timers[$name]['end']))
            { $this->end($name); }
            $retval = "$name: {$this->timers[$name]['total']} seconds<br>\n"; 
        }
        else
        {
            foreach($this->timers as $name=>$value)
            { 
                if(!isset($this->timers[$name]['end']))
                { $this->end($name); }
                
                $retval .= "$name: {$this->timers[$name]['total']} seconds<br>\n"; 
            }
        }
        return '<hr>' . $retval . '<hr>';
    }
}

?>