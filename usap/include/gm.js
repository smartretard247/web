

function gm(gmpos,wnd){
  switch (wnd) {
    case 1:
      var OpenSubX = (screen.width/2)-520;
      var OpenSubY = (screen.height/2)-520;
      var pos = "left="+OpenSubX+",top="+OpenSubY;
      OpenSubWindow = window.open(gmpos,"Map","toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, copyhistory=no, resizable=yes, width=520, height=520,"+pos);
      break;
    case 2:
      var OpenSubX = (screen.width/2)-620;
      var OpenSubY = (screen.height/2)-520;
      var pos = "left="+OpenSubX+",top="+OpenSubY;
      OpenSubWindow = window.open(gmpos,"Profile","toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, copyhistory=no, resizable=yes, width=700, height=480,"+pos);
      break;
    case 3:
      var OpenSubX = (screen.width/2)-900;
      var OpenSubY = (screen.height/2)-550;
      var pos = "left="+OpenSubX+",top="+OpenSubY;
      OpenSubWindow = window.open(gmpos,"Forms","toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, copyhistory=no, resizable=no ,width=900, height=550,"+pos);
      break;
    case 4:
      var OpenSubX = (screen.width/2)-900;
      var OpenSubY = (screen.height/2)-550;
      var pos = "left="+OpenSubX+",top="+OpenSubY;
      OpenSubWindow = window.open(gmpos,"Forms","toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, copyhistory=no, resizable=no ,width=900, height=550,"+pos);
      break;
    default:
  }
}
