body {
   background: white;
   font-size: 12pt;
   }
#wrapper {
   display: none;
   }
#content {
   width: auto;
   margin: 0 5%;
   padding: 0;
   border: 0;
   float: none;
   color: black;
   background: transparent none;
   margin-left: 10%;
   padding-top: 1em;
   border-top: 1px solid #930;
   }
a:link, a:visited {
   color: #520;
   background: transparent;
   font-weight: bold;
   text-decoration: underline;
   }
#content a:link:after, #content a:visited:after {
   content: " (" attr(href) ") ";
   font-size: 90%;
   }
#content a[href^="/"]:after {
   content: " (http://www.alistapart.com" attr(href) ") ";
   }
