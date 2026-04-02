let test = [];
let count_questions = 0;
let current_question = 0;
let index = 0;
let score = 0;
let passing_score = 0;
let wrong_answers = [];

function get_test(test_id) {

    const form_data = {
        'action': 'get_test',
        'test_id': test_id,
    }

    $.ajax({
        url: '/scripts/testing/testing.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            test = response;
            let ids_questions = test.map(function(item) {
                return item.id;
            });
            count_questions = [...new Set(ids_questions)].length;  
            passing_score = count_questions - 1;        
            $('.testing').empty();
            show_question();
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
    
}

function show_question() {
    let question = test[index]['name_question'];
    let id = test[index]['id']; 
    let answers = '';
    let number = 1;
    while(test.length > index && test[index]['id'] === id) {
        answers += `<div class="mb-3 ml-3">
                        <input class="form-check-input mt-2" type="radio" id="answer${number}" name="answer" value="${index}" />
                        <label class="form-check-label testing-answer" for="answer${number}">${test[index]['name_answer']}</label>
                    </div>`;     
        index++;     
        number++;          
    }

    $(".testing").append(`  
                            <div class="testing-progress">Вопрос ${current_question + 1} из ${count_questions}</div>
                            <div class="testing-question">${question}</div>
                            <form class="mt-4">
                                ${answers}
                                <div class="mt-5">
                                    <button id="answer-question" class="btn btn-info testing-button" type="button">Ответить</button>
                                </div>
                            </form>
                        `);
}

function check_answer() {
    let id_selected_answer = $('input[name="answer"]:checked').val();
    score += +test[id_selected_answer]['is_correct'];
    if(+test[id_selected_answer]['is_correct'] === 0) {
        wrong_answers.push({
            number: current_question + 1,
            question: test[id_selected_answer]['name_question'],
        }); 
    }
}

function show_result() {
    let testing_result_text = '';
    let wrong_answers_table = '';


    if(score >= passing_score) {
        testing_result_text += '<div class="testing-result-text mb-3 text-success"><b>Тест сдан</b></div>';
    }
    else {
        testing_result_text += '<div class="testing-result-text mb-3 text-danger"><b>Тест не сдан</b></div>';
    }    

    if(wrong_answers.length !== 0) {
        wrong_answers_table += `<div class="testing-table-errors">
                                    <div class="mb-1 mt-3"><b>Вопросы, где были допущены ошибки:</b></div>
                                        <table class="table table-striped table-bordered">
                                          <thead>
                                            <tr>
                                              <th scope="col">№</th>
                                              <th scope="col">Вопрос</th>
                                            </tr>
                                          </thead>
                                          <tbody>`;

        wrong_answers.forEach((wrong_answer) => {
            wrong_answers_table += `<tr>
                                      <td>${wrong_answer.number}</td>
                                      <td>${wrong_answer.question}</td>
                                    </tr>`;
        });
        wrong_answers_table += `    </tbody>
                                  </table>
                                </div>`;
    }    

    $(".testing").append(`  
                            <div class="testing-result text-center">
                                ${testing_result_text}
                                <div class="testing-result-number mb-1"><b>Результат теста</b></div>
                                <div class="testing-result-number">Ваши баллы: <b>${score}</b></div>
                                <div class="testing-result-number">Проходной балл: <b>${passing_score}</b></div>
                                ${wrong_answers_table}
                                <a href="/templates/pages/initial-training-course.php" class="btn btn-info testing-button mt-4">Вернуться к курсу</a>
                            </div>
                        `); 
                          
}

function save_result() {
    const user_test_id = $('.education-title').attr('data-user-test-id');
    const is_done = score >= passing_score ? 'сдано' : 'не сдано'; 

    const form_data = {
        'action': 'save_result',
        'user_test_id': user_test_id,
        'score': score,
        'is_done': is_done,
    }

    $.ajax({
        url: '/scripts/testing/testing.php',
        method: 'POST',
        data: form_data,
        success: function (response) {  
            $('.testing').empty();
            show_result();
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    }); 
   
}

$(document).ready(function (e) {
    $('#start-testing').on('click', function (e) {
        const test_id = $('#start-testing').attr('data-test-id');
        get_test(test_id);
    });

    $('body').on('click', '#answer-question', function (e) {
        if($('input[name="answer"]:checked').val()) {
            check_answer();
            if(test.length !== index) {
                current_question++;
                $('.testing').empty();
                show_question(); 
            } else {
                save_result();
            }            
        }
        else {
            alert('Ответ на вопрос обязательный!');
        }
    });

});