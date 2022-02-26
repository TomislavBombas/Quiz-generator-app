/* predefined animations
sliding-righttoleft, fade
*/
let defaultVars = {timing:600, backgroundColor: '#ffffff', imageMode:'vignete', animation: 'fade', useFullPage: false, showInterface: true, showMenu: true, automaticNext: true, endScreen: {results: {mode:'percent'}, image:{option:'background', url:'images/endscreen.jpg'}, message: 'End message goes here'} }
var settingVars;
//load options from JSON file;
fetch("./js/options.json").then(response => { return response.json(); }).then( jsondata => { settingVars = jsondata; } );




let quiz = {
    start: ()=>{
        // Set element where the quiz is to be injected
        quiz.root = document.getElementById('quiz');

        //declaring start variables
        quiz.question = null;
        quiz.answers = [];
        quiz.current = 0;

        //call the function setting styles and classes from loaded variables
        quiz.settings = quiz.setStage();

        //start the quiz
        quiz.draw(questions[0]);
    },
    setStage: ()=>{
        // Setting various styles and classes from loaded variables
        // if no variables are set in the variable file default values declaired on top of this file will be used
        if (!settingVars) settingVars = defaultVars;
        
        if (!settingVars['showMenu']) try { let header = document.getElementsByTagName('header')[0]; header.parentNode.removeChild(header); } catch (e) {console.log(e)}

        if (settingVars['useFullPage']) {
            document.body.style.backgroundColor = settingVars['backgroundColor'];
            quiz.root.parentNode.classList.remove('wrapper');
        }
        quiz.root.style.backgroundColor = settingVars['backgroundColor'];
        quiz.root.classList.add('image-mode-'+settingVars['imageMode']);
        document.body.classList.add('image-mode-'+settingVars['imageMode']);
        quiz.root.classList.add('animation-'+settingVars['animation']);

        document.body.classList.remove('invisible'); // this makes the whole thing visible, after the settings are set
        return settingVars;
    },
    draw: (q)=> {
        if (quiz.question) {
            quiz.question.classList.remove('animateIn');
            quiz.question.classList.add('animateOut');
        }
        setTimeout(()=>{
            if (quiz.question) {
                quiz.question.classList.remove('animateOut');
                quiz.question.parentNode.removeChild(quiz.question);
            }
            let question = quiz.createQuestionBlock(q);
            question.classList.add('animateIn');
            quiz.root.appendChild(question);
            quiz.question = question;
        }, quiz.settings['timing'])
    },
    questionWrapper: new Object(),
    createQuestionBlock: (q) => {
        //This part just generates DOM elements for the question
        quiz.questionWrapper = document.createElement('div');
        quiz.questionWrapper.classList.add('question-wrapper');
        quiz.questionWrapper.setAttribute('data', q['correct answer']);

        let image = document.createElement('div');
        image.classList.add('image-wrapper');
        let img = document.createElement('img');
        img.setAttribute('src', q['image']);
        image.appendChild(img);
        quiz.questionWrapper.appendChild(image);

        let question = document.createElement('div');
        question.classList.add('question-content');
        quiz.questionWrapper.appendChild(question);

        let qst = document.createElement('h2');
        qst.innerHTML = q['question'];
        question.appendChild(qst);

        let answers = document.createElement('div');
        answers.classList.add('answers-wrapper');
        question.appendChild(answers);
        let answ = document.createElement('ul');
        answers.appendChild(answ);

        Object.values(q['answers']).forEach((item,index)=>{
            let answr = document.createElement('li');
            answr.innerHTML = item;
            answr.setAttribute('data', index);
            answ.appendChild(answr);
            answr.addEventListener('click', quiz.checkAnswer);;
        });

        return quiz.questionWrapper;
    },
    checkAnswer: (ev) => {
        // Stop double click, quick clicks, etc
        ev.preventDefault();
        ev.stopPropagation();
        document.body.classList.add('no-click');
        try {quiz.questionWrapper.getElementsByClassName('chosen-answer')[0].classList.remove('chosen-answer');} catch (e){console.log(e);}
        ev.target.classList.add('chosen-answer');
        //get chosen answer number
        let child = ev.target;
        //Go to next question if there is one, else go to Results
        quiz.doNext(child);
    },
    doNext: (child)=> {
        if (settingVars['automaticNext']) {
            setTimeout(()=>{quiz.goNext(child)}, 300);
        } else {
            quiz.insertNextButton(child);
        }
    },
    insertNextButton: (child)=>{
        if (Object.values(quiz.questionWrapper.getElementsByClassName('next-button')).length == 0) {  
            let qw = quiz.questionWrapper; //document.getElementsByClassName('question-wrapper')[0];
            let nextBtn = document.createElement('a');
            nextBtn.innerHTML = 'next';
            nextBtn.setAttribute('href', '#');
            nextBtn.classList.add('next-button');
            nextBtn.classList.add('button');
            nextBtn.classList.add('btn');
            qw.appendChild(nextBtn);
            nextBtn.addEventListener('click', (ev)=>{
                ev.preventDefault();
                ev.stopPropagation();
                quiz.goNext(child);
            });
        }
    },
    goNext: (child)=>{
        let parent = child.parentNode;
        let index = Array.prototype.indexOf.call(parent.children, child);
        let correctAnswer = Number(questions[quiz.current]['correct answer']);
        let chosenAnswer = [child.innerHTML, index + 1, correctAnswer];
        quiz.answers.push(chosenAnswer);
        if (quiz.current < questions.length-1) {
            quiz.current ++;
            quiz.draw(questions[quiz.current]);
            document.body.classList.remove('no-click');
        } else {
            quiz.doResults.setup()
        }
    },
    goPrev: ()=>{
        if (quiz.current > 0) quiz.current --;
        quiz.draw(questions[quiz.current]);
        document.body.classList.remove('no-click');
    },
    doResults: { 
        endWrapper: new Object(),
        setup: ()=>{
            quiz.questionWrapper.innerHTML = '';
            if (settingVars['endScreen']) {
                if (settingVars['endScreen']['image']) quiz.doResults.setBackgroundImage();
                quiz.doResults.endWrapper = document.createElement('div');
                quiz.doResults.endWrapper.classList.add('end-content-wrapper');
                quiz.questionWrapper.appendChild(quiz.doResults.endWrapper)
                quiz.doResults.showResults();
                if (settingVars['endScreen']['message']) quiz.doResults.setEndMessage();
            } else {
                let correct = 0;
                Object.values(quiz.answers).forEach ((item, index)=>{
                    if (Number(item[1]) == Number(item[2])) {
                        correct ++;
                    }
                });
                console.log("correct number of answers is ", correct, "from", Object.values(quiz.answers).length);
                let percent = ((correct / Object.values(quiz.answers).length) * 100) + "%";
                quiz.questionWrapper.innerHTML = '<p style="text-align: center;">Tačno ste odgovorili ste ' + percent + "</p>";
            }
        },
        setBackgroundImage: ()=>{
            let imageUrl = settingVars['endScreen']['image']['url'];
            let imageWrapper = document.createElement('div');
            imageWrapper.classList.add('end-image-wrapper');
            let image = document.createElement('img');
            image.setAttribute('src', imageUrl);
            imageWrapper.appendChild(image)
            image.classList.add('end-image');
            if (settingVars['endScreen']['image']['option'] == 'vignete') { 
                imageWrapper.classList.add('vignete');
                quiz.root.classList.add('end-image-mode-vignete');
                quiz.root.parentNode.classList.add('wrapper');
            } else { 
                imageWrapper.classList.add('background');
                quiz.root.classList.add('end-image-mode-background');
                quiz.root.parentNode.classList.remove('wrapper');
            }
            quiz.questionWrapper.appendChild(imageWrapper);
        },
        setEndMessage: ()=>{
            let msg = settingVars['endScreen']['message'];
            let endMessageWrapper = document.createElement('div');
            endMessageWrapper.classList.add('end-message-wrapper');
            let textWrapper = document.createElement('p');
            endMessageWrapper.appendChild(textWrapper);
            let txt = document.createTextNode(msg);
            textWrapper.appendChild(txt);
            quiz.doResults.endWrapper.appendChild(endMessageWrapper);
        },
        showResults: ()=>{
            let mode
            try {mode = settingVars['endScreen']['resuts']['mode'];} catch (e) {console.log (e)}
            if (!mode) mode='percent';
            let resultsWrapper = document.createElement('div');
            resultsWrapper.classList.add('results-wrapper');
            let textWrapper = document.createElement('p');
            resultsWrapper.appendChild(textWrapper);
            let msg = "conditions";
            if (mode == 'percent') {
                let correct = 0;
                Object.values(quiz.answers).forEach ((item, index)=>{
                    if (Number(item[1]) == Number(item[2])) {
                        correct ++;
                    }
                });
                let percent = ((correct / Object.values(quiz.answers).length) * 100) + "%";
                msg = "You answered " + percent + " correctly";
            } else {
                let count = 0;
                let value = 0;
                Object.values(quiz.answers).forEach ((item, index)=>{
                    let count1 = Object.values(quiz.answers).filter(x => x ==  Number(item[1])).length;
                    if (count < count1) {count = count1; value = Number(item[1])}
                });
                msg = "Most used answer was no " + value;
            }
            let txt = document.createTextNode(msg);
            textWrapper.appendChild(txt);
            quiz.doResults.endWrapper.appendChild(resultsWrapper);
        },
    },
}

function doHamburger (){
    let hamburger = document.getElementById('hamburger');
    try {
        hamburger.addEventListener('click', ()=>{
            hamburger.parentNode.classList.toggle('visible');
            setTimeout(()=>{hamburger.parentNode.classList.toggle('open');}, 100);
        })
    } catch (e) {
        console.log(e);
    }
}
function startMe (evt) {
    setTimeout(()=>{
        if (Object.values(questions).length > 0) try{quiz.start()} catch(e){console.log(e)};
    },250);
    doHamburger();
}

window.addEventListener('load', startMe); //Wait for page to load to start scripts