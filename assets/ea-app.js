import 'ckeditor';
import './js/MinecraftColorCodes.3.7';

(()=> {
    let response = document.querySelector('.response').innerHTML;
    document.querySelector('.response').innerHTML = response.replaceColorCodes();
})();