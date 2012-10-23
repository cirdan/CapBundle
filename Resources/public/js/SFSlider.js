function SFslider(){
  this.init();
}

SFslider.prototype = {
  init: function(){
    $('#SFSliderPlus').click(function(){
      CAPSlider.rewind();//TODO: Comment faire pour ne pas utilier une instance ?
    });
    $('#SFSliderMoins').click(function(){
      CAPSlider.toEnd();//TODO: Comment faire pour ne pas utilier une instance ?
    });
    this.showControls();
  },
  toEnd: function(){
    if(($('#summary>li:last').position().left+$('#summary>li:last').width()-70)>$("#summaryContainer").width()){
      var slider=this;//TODO : comment passer le slider autrement ?
      $('#summaryInner').animate(
        {
            left: this.minPos()
        },
        function(){
          slider.showControls();
        }
      );
    }
  },
  showControls: function(){
    if($("#summaryContainer").width()<($('#summary>li:last').position().left+$('#summary>li:last').width()-70)){
      $('#SFSliderMoins').fadeIn(500);
      $('#SFSliderPlus').fadeIn(500);
    }else{
      $('#SFSliderMoins').fadeOut(500);
      $('#SFSliderPlus').fadeOut(500);
    }
  },
  rewind: function(){
    $('#summaryInner').animate({left: this.maxPos()});
  },
  center: function(item,glow){
    var slider=this;//TODO : comment passer le slider autrement ?
    $('#summaryInner').animate(
    {
      left: this.centerPos(item)
    },function(){
      if(glow){
        slider.glow(item);
      }
      slider.showControls();
    }
    );
  },
  glow: function(item){
    $(item).animate(
        {boxShadow: '0px 0px 30px #aaaaaa'}
        ,200
        ,function(){
          $(this).animate({boxShadow: '0px 1px 1px rgba(0,0,0,0.05)'},200);
        }
      );
  },
  minPos: function(){
    return 0
            -$('#summary>li:last').position().left
            -$('#summary>li:last').width()
            -70
            +$("#summaryContainer").width();
  },
  maxPos: function(){
    return 0;
  },
  centerPos: function(item){
    if(($('#summary>li:last').position().left+$('#summary>li:last').width())>$("#summaryContainer").width()){
      return Math.max(Math.min(this.absoluteCenterPos(item),this.maxPos()),this.minPos());
    }else{
      return this.maxPos();
    }
  },
  absoluteCenterPos: function(item){
    return $("#summaryContainer").width()/2
        -$(item).position().left
        -1.0*$(item).css("marginLeft").replace("px", "")
        -$(item).width()/2;
  }
}
