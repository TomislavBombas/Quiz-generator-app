var questions = [];
var client = new XMLHttpRequest();
client.open('GET', './data/questions.csv');
client.onreadystatechange = (ev)=>{
    if (ev.currentTarget.readyState == 3 ) {
        console.log(ev.currentTarget.readyState);
        let arrayOfLines = client.responseText.match(/[^\r\n]+/g);
        arrayOfLines[0] = arrayOfLines[0].replace(/\s$/,"");
        arrayOfLines[0] = arrayOfLines[0].replaceAll('"',"");
        let varNames = arrayOfLines[0].split(',');
        if (questions.length > 0) questions = []; 
        Object.values(arrayOfLines).forEach((item, index)=>{
            if (index > 0) {
                let questionDetails = item.split(',');
                let singeQuestion = [];
                let answers = [];
                Object.values(questionDetails).forEach((item, index)=>{
                    if (varNames[index].includes('answer') && !varNames[index].includes('correct')) {
                        item = item.replaceAll('"',"");
                        answers.push(item);
                    } else {
                        item = item.replaceAll('"',"");
                        singeQuestion[varNames[index]] = item;
                    } 
                });
                singeQuestion['answers'] = answers;
                questions.push (singeQuestion);
            }
        });
    }
    
}
client.send();