
function openModalTray(url, txtTitle)
{
    var _url = checkoutredir_baseurl + url;
    
    if(txtTitle === null || txtTitle === '') {
        txtTitle = 'Validação das informações do Anúncio';
    }
    winCompare = new Window({
        className:'magento',
        title:txtTitle,
        url:_url,
        width:820,
        height:600,
        minimizable:false,
        maximizable:false,
        showEffectOptions:{
            duration:0.4
        },
        hideEffectOptions:{
            duration:0.4
        }
    });
    winCompare.setZIndex(100000);
    winCompare.showCenter(true);
}