$(function(){
  'use strict'

  var ch1 = new Rickshaw.Graph({
    element: document.querySelector('#ch1'),
    renderer: 'area',
    max: 80,
    series: [{
      data: [
        { x: 0, y: 40 },
        { x: 1, y: 49 },
        { x: 2, y: 38 },
        { x: 3, y: 30 },
        { x: 4, y: 32 },
        { x: 5, y: 40 },
        { x: 6, y: 20 },
        { x: 7, y: 10 },
        { x: 8, y: 20 },
        { x: 9, y: 25 },
        { x: 10, y: 35 },
        { x: 11, y: 20 },
        { x: 12, y: 40 }
      ],
      color: 'rgba(255,255,255,0.5)'
    }]
  });
  ch1.render();

  // Responsive Mode
  new ResizeSensor($('.br-mainpanel'), function(){
    ch1.configure({
      width: $('#ch1').width(),
      height: $('#ch1').height()
    });
    ch1.render();
  });

  var ch2 = new Rickshaw.Graph({
    element: document.querySelector('#ch2'),
    renderer: 'area',
    max: 80,
    series: [{
      data: [
        { x: 0, y: 40 },
        { x: 1, y: 15 },
        { x: 2, y: 38 },
        { x: 3, y: 40 },
        { x: 4, y: 32 },
        { x: 5, y: 50 },
        { x: 6, y: 65 },
        { x: 7, y: 70 },
        { x: 8, y: 45 },
        { x: 9, y: 55 },
        { x: 10, y: 60 },
        { x: 11, y: 50 },
        { x: 12, y: 40 }
      ],
      color: 'rgba(255,255,255,0.5)'
    }]
  });
  ch2.render();

  // Responsive Mode
  new ResizeSensor($('.br-mainpanel'), function(){
    ch2.configure({
      width: $('#ch2').width(),
      height: $('#ch2').height()
    });
    ch2.render();
  });

  var ch3 = new Rickshaw.Graph({
    element: document.querySelector('#ch3'),
    renderer: 'area',
    max: 80,
    series: [{
      data: [
        { x: 0, y: 40 },
        { x: 1, y: 45 },
        { x: 2, y: 30 },
        { x: 3, y: 40 },
        { x: 4, y: 50 },
        { x: 5, y: 40 },
        { x: 6, y: 20 },
        { x: 7, y: 10 },
        { x: 8, y: 20 },
        { x: 9, y: 25 },
        { x: 10, y: 35 },
        { x: 11, y: 20 },
        { x: 12, y: 40 }
      ],
      color: 'rgba(255,255,255,0.5)'
    }]
  });
  ch3.render();

  // Responsive Mode
  new ResizeSensor($('.br-mainpanel'), function(){
    ch3.configure({
      width: $('#ch3').width(),
      height: $('#ch3').height()
    });
    ch3.render();
  });

  var ch4 = new Rickshaw.Graph({
    element: document.querySelector('#ch4'),
    renderer: 'area',
    max: 80,
    series: [{
      data: [
        { x: 0, y: 40 },
        { x: 1, y: 45 },
        { x: 2, y: 30 },
        { x: 3, y: 40 },
        { x: 4, y: 50 },
        { x: 5, y: 40 },
        { x: 6, y: 20 },
        { x: 7, y: 10 },
        { x: 8, y: 20 },
        { x: 9, y: 25 },
        { x: 10, y: 35 },
        { x: 11, y: 20 },
        { x: 12, y: 40 }
      ],
      color: 'rgba(255,255,255,0.5)'
    }]
  });
  ch4.render();

  // Responsive Mode
  new ResizeSensor($('.br-mainpanel'), function(){
    ch4.configure({
      width: $('#ch4').width(),
      height: $('#ch4').height()
    });
    ch4.render();
  });

  var newCust = [[0, 8], [1, 7], [2,7], [3, 8], [4, 7], [5, 8], [6, 9], [7, 9], [8, 9], [9, 8], [10, 9], [11, 8], [12, 7], [13,8], [14, 7], [15, 7], [16, 8], [17, 9], [18, 9], [19, 10]];
  var retCust = [[0, 1], [1, 2], [2,3], [3, 3], [4, 2], [5, 3], [6, 4], [7, 5], [8, 4], [9, 5], [10, 4], [11, 4], [12, 3], [13,4], [14, 4], [15, 5], [16, 5], [17, 4], [18, 6], [19, 7]];
});
