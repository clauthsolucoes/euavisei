function goPage(url, link){
    let arr = url.split('/');
    let end = arr[arr.length - 1].length;
    let text = url.substring(0, url.length - end);
    let newUrl = text + link;
    console.log(newUrl);
    window.location.href = newUrl;
}