import 'ckeditor';
import replaceColorCodes from './js/MinecraftColorCodes.3.7'

(()=> {
    document.querySelector('.response').innerHTML = replaceColorCodes( document.querySelector('.response').innerHTML);
})();