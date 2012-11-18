function SFChronometer(container,options){
  this.container=container;
  if(typeof options !== 'undefined'){
    if(typeof options.displayMsecs !== 'undefined'){
      this.options.displayMsecs=options.displayMsecs;
    }
    if(typeof options.buttonStart !== 'undefined'){
      this.buttonStart=options.buttonStart;
    }
    if(typeof options.buttonReset !== 'undefined'){
      this.buttonReset=options.buttonReset;
    }
    if(typeof options.buttonStop !== 'undefined'){
      this.buttonStop=options.buttonStop;
    }
  }
  this.init();
}

SFChronometer.prototype = {
  container: "#SFChronometer",
  startTime: 0,
  startChr: 0,
  endChr: 0,
  diff: 0,
  isRunning: false,
  buttonStart: ".SFChronStart",
  buttonStop: ".SFChronStop",
  buttonReset: ".SFChronReset",
  chronoString: ".SFChronTime",
  options:{
    displayMsecs:true
  },
  init: function(){
    var self=this;
    $(this.container).append('<span class="SFChronTime"><span class="SFCronTimeHR"></span></span>');
    $(this.container + " .SFChronTime").append('<span class="SFCronTimeHR">0</span>:');
    $(this.container + " .SFChronTime").append('<span class="SFCronTimeMIN">00</span>:');
    $(this.container + " .SFChronTime").append('<span class="SFCronTimeSEC">00</span>');
    if(this.options.displayMsecs){
      $(this.container + " .SFChronTime").append(':<span class="SFCronTimeMS">000</span>');
    }
    if(!$(this.buttonStart).length){
      $(this.container).append('<button class="SFChronStart">start</button>');
    }
    if(!$(this.buttonStop).length){
      $(this.container).append('<button class="SFChronStop">stop</button>');
    }
    if(!$(this.buttonReset).length){
      $(this.container).append('<button class="SFChronReset">reset</button>');
    }
    $(this.buttonStart).click(function(){self.chronoStart();});
    $(this.buttonStop).click(function(){self.chronoStop();}).hide();
    $(this.buttonReset).click(function(){self.chronoReset();}).hide();
  },
  getCurrent: function(fmt){
    this.endChr = new Date();
    this.diff = this.endChr - this.startChr;
    this.diff = new Date(this.diff);

    switch (fmt) {
      case "objDate": 
        return this.diff;
        break;
      case "obj": 
        var msec = this.diff.getMilliseconds();
        var sec = this.diff.getSeconds();
        var min = this.diff.getMinutes();
        var hr = this.diff.getHours()-1;
        if (min < 10){
          min = "0" + min;
        }
        if (sec < 10){
          sec = "0" + sec;
        }
        if(msec < 10){
          msec = "00" +msec;
        }
        else if(msec < 100){
          msec = "0" +msec;
        }
        return {
          hours: hr,
          mins: min,
          secs: sec,
          msecs: msec
        }
        break;
      default: 
        var msec = this.diff.getMilliseconds();
        var sec = this.diff.getSeconds();
        var min = this.diff.getMinutes();
        var hr = this.diff.getHours()-1;
        if (min < 10){
          min = "0" + min;
        }
        if (sec < 10){
          sec = "0" + sec;
        }
        if(msec < 10){
          msec = "00" +msec;
        }
        else if(msec < 100){
          msec = "0" +msec;
        }
        var toReturn = hr + ":" + min + ":" + sec;
        if(this.options.displayMsecs){
          return  toReturn + ":" + msec;
        }else{
          return  toReturn;
        }
        break;
    }
  },
  getTime: function(){
    var now = new Date();
    var sec = now.getSeconds();
    var min = now.getMinutes();
    var hr = now.getHours();
    if (min < 10){
      min = "0" + min;
    }
    if (sec < 10){
      sec = "0" + sec;
    }
    return hr + ":" + min + ":" + sec;

  },
  chrono: function(){
    var state=this.getCurrent("obj");
    $(this.container + " .SFChronTime .SFCronTimeHR").html(state.hours);
    $(this.container + " .SFChronTime .SFCronTimeMIN").html(state.mins);
    $(this.container + " .SFChronTime .SFCronTimeSEC").html(state.secs);
    if(this.options.displayMsecs){
      $(this.container + " .SFChronTime .SFCronTimeMS").html(state.msecs);
    }
    var self=this;
    if(this.isRunning){
      setTimeout(function(){self.chrono()}, 100);
    }
  },
  chronoStart: function(){
    var self=this;
    this.switchStartStop();
    $(this.buttonReset).hide();
    this.startChr = new Date()-this.diff;
    this.startChr = new Date(this.startChr);
    this.isRunning = true;
    this.chrono();
  },
  chronoReset: function(){
    $(this.buttonReset).hide();
    this.endChr = 0;
    this.diff = 0;
    this.startTime = 0;
    this.startChr = 0;
    this.endChr = 0;
    this.isRunning = false;
    $(this.container + " .SFChronTime .SFCronTimeHR").html("0");
    $(this.container + " .SFChronTime .SFCronTimeMIN").html("00");
    $(this.container + " .SFChronTime .SFCronTimeSEC").html("00");
    if(this.options.displayMsecs){
      $(this.container + " .SFChronTime .SFCronTimeMS").html("000");
    }
  },
  chronoStop: function(){
    var self=this;
    this.isRunning = false;
    this.switchStartStop();
    $(this.buttonReset).show();
  },
  switchStartStop: function(){
    $(this.buttonStop).toggle();
    $(this.buttonStart).toggle();
  }
}
