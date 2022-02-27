function startMe (){
    let groupButtons = document.querySelectorAll('.button-group .btn');
    Object.values(groupButtons).forEach((item, index)=>{
        let chosen;
        try { chosen = item.parentNode.getElementsByClassName('chosen')[0]; } catch (e) { console.log(e) }
        if (chosen) {
            let varname = chosen.getAttribute('data');
            chosen.parentNode.parentNode.setAttribute('data', varname);
        }
        item.addEventListener('click', (ev)=>{
            ev.preventDefault();
            ev.stopPropagation();
            let chosen;
            try { chosen = ev.target.parentNode.getElementsByClassName('chosen')[0]; } catch (e) { console.log(e) }
            if (chosen) chosen.classList.remove('chosen');
            ev.target.classList.add('chosen');
            let varname = ev.target.getAttribute('data');
            ev.target.parentNode.parentNode.setAttribute('data', varname); 
        })
    });
}
window.addEventListener('load', startMe);