import './MinecraftColorCodes.3.7';

(()=> {
    if (document.querySelector('.response')) {
        let response = document.querySelector('.response').innerHTML;
        document.querySelector('.response').innerHTML = '';
        document.querySelector('.response').appendChild(response.replaceColorCodes());
    }
})();