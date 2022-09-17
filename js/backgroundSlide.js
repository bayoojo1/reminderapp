//Array of images which you want to show: Use path you want.
var images=new Array('./images/banners/welcomemono.png','./images/banners/p-campaignmono.jpg','./images/banners/reminder-appmono.jpg','./images/banners/p-assistancemono.jpg','./images/banners/apimono.jpg','./images/banners/contentprovidermono.jpg','./images/banners/p-announcementmono.jpg');
var nextimage=0;
doSlideshow();

function doSlideshow(){
    if(nextimage>=images.length){nextimage=0;}
    $('.loginpage')
    .css('background-image','url("'+images[nextimage++]+'")')
    .fadeIn(500000,function(){
        setTimeout(doSlideshow,10000);
    });
}
