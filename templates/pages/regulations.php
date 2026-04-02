<?

require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/unprocessed_base/unprocessed_base.php';

if(!$_SESSION['login_role']) {
    header('Location: /'); 
}

$err = isset($_GET['err']) ? 'Неверный логин или пароль' : false;
?>
<!-- Begin page -->
<div id="wrapper">

    <!-- Левое меню -->
    <? require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/sidebar_left.php'; ?>

    <div class="content-page">
        <!-- Верхнее меню -->
        <? require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/sidebar_top.php'; ?>

        <!-- Start Page content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h1 class="education-title">📑Кодекс МКГ</h1>
                            <h2 class="text-center mt-5">💎О нас</h2>    
                            <p class="education-paragraph-text">«Маркетинг Консалтинг Групп» - современная, профессиональная команда единомышленников, которая помогает бизнесам расти и развиваться.</p>
                            <p class="education-paragraph-text">Компания основана в 2018 году. Мы автоматизируем бизнес-процессы, специализируемся на маркетинге, консалтинге, лидогенерации.</p>
                            <p class="education-paragraph-text">Внедряем современные IT-решения (разработка CRM-систем).</p> 
                            <p class="education-paragraph-text">Занимаемся построением call-центров, набором и обучением сотрудников.</p>
                            <div class="education-color-background d-flex" style="gap: 10px;">
                                <div>
                                    <h2>Слово основателя</h2> 
                                    <p class="education-paragraph-text">Уважаемые коллеги!</p>
                                    <p class="education-paragraph-text">Рад приветствовать вас в команде! Наша компания зарекомендовала себя как надежный партнер, и мы гордимся достигнутыми успехами и крепкими партнерскими отношениями.</p> 
                                    <p class="education-paragraph-text">Мы стремимся развивать профессионализм и реализовать потенциал каждого сотрудника, что помогает нам успешно справляться с любыми вызовами.</p>
                                    <p class="education-paragraph-text">С уважением,<br>Григоренко Евгений Андреевич<br>Генеральный директор ООО «МКГ»</p>
                                </div>
                                <div class="pt-3 pb-3 pr-3 text-right"><img src="/assets/images/regulations/img-1.jpg" style="width: 75%;"></div>
                            </div>
                            <h2 class="text-center mt-5">💎Наша миссия</h2> 
                            <p class="education-paragraph-text">Наша миссия заключается в двусторонней помощи клиентам и партнерам, обеспечивая качество и контроль результатов. Мы создаем ценность на основе статистики и метрик, придерживаясь принципов открытости.</p>
                            <p class="education-paragraph-text">Мы честны с нашими клиентами, партнерами, сотрудниками и сами с собой, нацелены на доверительные и долгосрочные отношения.</p>
                            <h2 class="text-center mt-5 mb-4">💎План адаптации</h2>
                            <div class="d-flex justify-content-between" style="gap: 10px;">
                                <div>
                                    <p class="education-paragraph-text">Что мы будем делать?</p>
                                    <p class="education-paragraph-text"><b>✅ Программа подготовки имеет 2 части:</b></p>
                                    <ul>
                                        <li class="education-list-item">Теорию (по должности)</li>
                                        <li class="education-list-item">Практику (по должности)</li>
                                    </ul>
                                    <p class="education-paragraph-text"><b>✍️ Теория по должности включает в себя:</b></p>
                                    <ul>
                                        <li class="education-list-item">Знание скрипта</li>
                                        <li class="education-list-item">Знание корпоративного кодекса</li>
                                        <li class="education-list-item">Знание отработки возражений</li>
                                        <li class="education-list-item">Умение работать в Скорозвоне, CRM</li>
                                    </ul>
                                    <p class="education-paragraph-text"><b>👋 Практика:</b></p>
                                    <ul>
                                        <li class="education-list-item">Тестовые звонки</li>
                                    </ul>
                                    <p class="education-paragraph-text"><b>✅ Чтобы пройти подготовку, необходимо:</b></p>
                                    <ul>
                                        <li class="education-list-item">Изучить теорию</li>
                                        <li class="education-list-item">Сдать тестирование по теории , итоговое тестирование</li>
                                        <li class="education-list-item">Сдать репетицию (репетиция скрипта)</li>
                                        <li class="education-list-item">Сдать аттестацию (проверяем практику работы)</li>
                                    </ul>
                                    <p class="education-paragraph-text"><b>⏳ Сроки:</b></p>
                                    <ul>
                                        <li class="education-list-item">На теорию у Вас есть не более 3 часов.</li>
                                        <li class="education-list-item">На практику -3-4 часа.</li>
                                    </ul>
                                    <p class="education-paragraph-text"><b>✅ Результат подготовки:</b></p>
                                    <ul>
                                        <li class="education-list-item">Вы знаете скрипт</li>
                                        <li class="education-list-item">Вы знаете, как правильно отрабатывать возражения</li>
                                        <li class="education-list-item">Вы получаете уверенность в своих знаниях</li>
                                    </ul>
                                    <p class="education-paragraph-text">👍 Все уверены в том, что работа будет эффективной и соответствовать ожиданиям.</p>
                                    <p class="education-paragraph-text">Отнеситесь к каждому блоку обучения максимально серьезно и внимательно.<br>Все блоки - составляющие будущей работы.</p>
                                </div> 
                                <div><img src="/assets/images/regulations/img-2.jpg" style="width: 100%;"></div>
                            </div>
                            <h2 class="text-center mt-5">💎Карьерный рост</h2>
                            <div class="education-color-background">
                                <p class="education-paragraph-text">Руководитель должен выявлять сильные стороны сотрудников и информировать их о возможностях карьерного роста и необходимых действиях для достижения результатов. <span class="bg-info">Задача сотрудника – демонстрировать, что он готов достигать результата💪</span></p>
                                <p class="education-paragraph-text">Прием и продвижение сотрудников основаны на их компетенциях и достижениях, без дискриминации по любым признакам. Компания ценит и поощряет сотрудников, способствующих ее успеху, и ожидает от них высоких стандартов.</p>
                            </div>
                            <h3 class="text-center">📈Истории успеха</h3> 
                            <div class="pt-3 pb-3" style="display: grid; grid-template-columns: repeat(3, 1fr); column-gap: 30px;">
                                <div>
                                   <div><img src="/assets/images/regulations/img-3.jpg" style="width: 100%; max-width: 550px;"></div>
                                   <div class="pt-3">
                                        <p class="education-paragraph-text"><b>Анжелика Вахидовна Загирова<br>
                                        Руководитель отдела лидогенерации ООО «МКГ»</b></p>
                                        <p class="education-paragraph-text"><b>👧ТОЧКА А:</b></p>
                                        <p class="education-paragraph-text">
                                        Работала в контакт-центре, бывший коллега организовал свое дело, и я решила присоединиться к его команде. Я не пожалела об этом!
                                        </p>
                                        <p class="education-paragraph-text"><b>👸ТОЧКА Б:</b></p>
                                        <p class="education-paragraph-text">
                                            Сейчас я — Руководитель отдела, быстро собрала команду и вывела сотрудников на выполнение KPI. Со мной весело, ведь я — королева лидов!
                                        </p>
                                        <p class="education-paragraph-text"><b>💭послание стажеру:</b><br>
                                        Если не сейчас, то когда? Ставьте цели, идите к ним, а мы вам поможем.                                                                         
                                        </p>
                                    </div> 
                                </div>
                                <div>
                                   <div><img src="/assets/images/regulations/img-4.jpg" style="width: 100%; max-width: 550px;"></div>
                                   <div class="pt-3">
                                        <p class="education-paragraph-text"><b>😎Владислав Витальевич Панчурин😎<br>
                                        Директор департамента продаж ООО «МКГ»</b></p>
                                        <p class="education-paragraph-text"><b>👼ТОЧКА А:</b></p>
                                        <p class="education-paragraph-text">
                                        Ранее я работал мастером по добыче рыбы, проводя месяцы в море. Хотя зарплата была неплохой, я понял, что хочу полноценной жизни и хороших доходов. Вместе с другом мы решили организовать свое дело. Поначалу было сложно, но энтузиазм и характер помогли преодолеть трудности.
                                        </p>
                                        <p class="education-paragraph-text">
                                        К 30 годам осознал важность дисциплины в жизни — тренировки, тайм-менеджмент, семья и карьера. Я доволен своим выбором и верю в нашу компанию, которая за короткий срок значительно выросла.
                                        </p>
                                        <p class="education-paragraph-text"><b>🧔ТОЧКА Б:</b></p>
                                        <p class="education-paragraph-text">
                                        Я  - руковожу продажами и коммуникацией с партнерами в этой компании. Более 200 постоянных и довольных партнеров.
                                        </p>
                                        <p class="education-paragraph-text"><b>💭послание стажеру:</b><br>
                                        Проявляйся и самовыражайся! На работу приходят за деньгами, а остаются с единомышленниками.                                                                     
                                        </p>
                                    </div> 
                                </div>
                                <div>
                                   <div><img src="/assets/images/regulations/img-5.jpg" style="width: 100%; max-width: 550px;"></div>
                                   <div class="pt-3">
                                        <p class="education-paragraph-text"><b>Аравин Владислав Константинович<br>
                                        Наставник стажеров</b></p>
                                        <p class="education-paragraph-text"><b>👼ТОЧКА А:</b></p>
                                        <p class="education-paragraph-text">
                                        Работал менеджером на линии КЦ, руководителем отделов в разных сферах, прошел «огонь и воду» пришел в компанию уже с опытом и с четким пониманием что можно улучшить.
                                        </p>
                                        <p class="education-paragraph-text"><b>🤴ТОЧКА Б:</b></p>
                                        <p class="education-paragraph-text">
                                        Сейчас я твой тренер😉<br>
                                        Моя задача максимально эффективно тебя подготовить к работе и поддержать.<br>
                                        Мне нравится работать с людьми и я всегда открыт к диалогу!
                                        </p>
                                        <p class="education-paragraph-text"><b>🥰Совет новеньким:</b> Понимаю как вам бывает сложно на адаптации, но вместе мы все преодолеем!💪</p>
                                        <p class="education-paragraph-text"><b>💭послание стажеру:</b><br>
                                        Я рад что нахожусь в компании в которой есть постоянная рабочая атмосфера и каждый из коллег может помочь!
                                        <br>Мы настоящая команда!                                                                   
                                        </p>
                                    </div> 
                                </div>
                            </div>
                            <div class="education-color-background"> 
                                <h3 class="text-center">📈Какие есть возможности роста в компании?</h3>
                                <p class="education-paragraph-text"><b>🤱Стажер – это Вы.</b></p> 
                                <p class="education-paragraph-text">Ваша задача пройти обучение и сдать теорию (тесты и задания), + подготовку к одной из должностей.</p>
                                <p class="education-paragraph-text"><b>👩Руководитель отдела/Супервайзер:</b></p> 
                                <ul>
                                    <li class="education-list-item">Претендует сотрудник, отработавший более 3-месяцев и успешно проявил себя в должности.</li>
                                    <li class="education-list-item">Соблюдает кодекс и правила компании.</li>
                                    <li class="education-list-item">Выполняет плановые показатели и приносит стабильный результат.</li> 
                                    <li class="education-list-item">Знает и понимает продукт.</li>
                                    <li class="education-list-item">Умеющий слышать, готовый учиться.</li>
                                    <li class="education-list-item">Обладающий коммуникативными и лидерскими навыками.</li>
                                </ul>
                                <p class="education-paragraph-text">    
                                *Заявление на повышение подается в письменном виде директору\HR и рассматривается в течение 2 недель. 
                                Обучение на руководителя проходит 2 недели в гибридном формате.<br>
                                Сдается аттестация и крутой руководитель готов😎
                                </p>
                            </div>
                            <h2 class="text-center mt-5 mb-4">💎Корпоративные правила</h2>
                            <h3 class="text-center">📋Ответственность сотрудника</h3>
                            <ul>
                                <li class="education-list-item">Добросовестно выполнять свои должностные обязанности, соблюдать внутренние стандарты Компании;</li> 
                                <li class="education-list-item">Заботиться о добром имени Компании, не наносить ущерб ее деятельности и репутации;</li>
                                <li class="education-list-item">Личные вещи хранятся в отведенном месте;</li>
                                <li class="education-list-item">Соблюдение дресс-кода (см.ниже);</li>
                                <li class="education-list-item">На рабочих местах должны быть ручка, блокнот, скрипт/ документация обучения;(остального не должно быть)</li>
                                <li class="education-list-item">Сменная обувь и верхняя одежда убирается в отведенное место;</li>
                                <li class="education-list-item">На столах отсутствует мусор, косметички, энергетики, мятые бумажки и прочее;</li> 
                                <li class="education-list-item">Задвигаем стулья, сидим ровно(не в позе «полулежа» и подобное);</li> 
                                <li class="education-list-item">После себя убираем места общего пользования: чайную зону, холодильник и микроволновку. Попользовался - убери за собой. (Протереть влажной салфеткой);</li> 
                                <li class="education-list-item">Приводим себя в порядок в туалетной комнате;</li> 
                                <li class="education-list-item">Придя на работу, переключаем телефон в беззвучный режим. Телефоном в рабочее время для личных вопросов пользоваться нельзя, для этого есть перерывы (каждый час + обед);</li> 
                                <li class="education-list-item">Между сотрудниками общаемся без мата, жаргона, насмешек и прочих токсичных элементов; 
                                <li class="education-list-item">Личные проблемы, жалобы неуместны в деловом этикете.</li> 
                                <li class="education-list-item">Если Вам с утра необходимо задержаться, предупреждаем руководителя вечером накануне.<br>Форс.мажорное опоздание – доказательство+объяснительная.
                                <br><br>😐Опоздания, тем более регулярные - безответственность, подрывающая работу всей команды.
                                <br>Не исполнение регламента: 1 раз выговор+объяснительная, 2 раз – санкция на усмотрение вышестоящего руководителя, 3 раз – увольнение.</li>
                                <li class="education-list-item">Если Вы заболеваете, необходимо предупредить своего руководителя о плохом самочувствии вечером накануне. Если Вы заболели резко утром, то необходимо вызвать врача и по выздоровлению предоставить справку. Предупредите, пожалуйста, своего руководителя как можно раньше (что бы руководитель сориентировал и настроил работу). Не исполнения регламента: 1 раз – выговор, 2 раз – увольнение.<br>
                                Сессия, отпуск, увольнение – согласуйте с руководителем как можно раньше, минимум за 2 недели;</li> 
                            </ul>
                            <br>
                            <ul>
                                <li class="education-list-item">Правило перерывов и обеда. В нашей компании есть определенные временные промежутки для отдыха, перерывов и обеда. Рабочий день начинается в 9:30, перерыв у нас без 10 каждого часа за исключением первого, а обед с 13:00 до 14:00. По окончанию этого времени сотрудник обязан быть на своем рабочем месте и приступить к работе.</li>
                                <li class="education-list-item">Правило общения в коллективе. Будьте дружелюбны и приветливы к своим коллегам, вы проводите вместе довольно много ежедневного времени, поэтому это очень важно. Следуйте правилу тона. Если вы хотите обратиться к руководителю либо к кому-то из коллег, то это стоит делать в полголоса так как: 1. Вы можете испортить запись разговора посторонними звуками. 2. Вы можете отвлечь коллегу, и он совершит ошибку.</li>
                            </ul>
                            <p class="education-paragraph-text">*Нарушение одного или нескольких из этих правил может привести к выговору. За систематическим нарушением данных правил следует дисциплинарное взыскание вплоть до увольнения.</p>
                            <div class="education-color-background"> 
                                <p class="education-paragraph-text"><b>👩ЧТО ПРИВЕТСТВУЕТСЯ?</b></p>
                                <ul>
                                    <li class="education-list-item">Задавать вопросы, если что-то не ясно / требует уточнения (Сейчас Ваша задача - учиться❤️)</li>
                                    <li class="education-list-item">Уточнять любые моменты, которые могут сказаться на качестве обучения / приобретения навыков</li>
                                    <li class="education-list-item">Качественная подготовка к срезам знаний</li>
                                    <li class="education-list-item">Выполнение задач в соответствии с инструкциями</li>
                                    <li class="education-list-item">Позитивный настрой и общение с коллегами в компании</li>
                                    <li class="education-list-item">Самостоятельное изучение дополнительных материалов</li>
                                </ul>
                            </div>
                            <p class="education-paragraph-text"><b>🪓ЧЕГО ДЕЛАТЬ НЕЛЬЗЯ?</b></p>
                            <ul>
                                <li class="education-list-item">Сидеть в телефоне и переписываться с друзьями на работе вместо работы</li>
                                <li class="education-list-item">Говорить с клиентами на "ты"</li>
                                <li class="education-list-item">Грубить клиентам</li>
                                <li class="education-list-item">Нарушать последовательность стадий скрипта и правила работы с клиентами</li>
                                <li class="education-list-item">НЕ задавать вопросы, если они есть</li>
                                <li class="education-list-item">Нарушать прочие правила, описанные в регламенте</li>
                            </ul>
                            <p class="education-paragraph-text">
                            В общем,  нарушать логичный порядок вещей.<br>
                            К сожалению, это не всем очевидно =(
                            </p>
                            <h3 class="text-center">📋Дресс-код</h3>
                            <div class="education-color-background"> 
                                <p class="education-paragraph-text">Достойный внешний вид, вежливое отношение и профессиональная компетентность вызывают уважение и интерес к Компании партнеров, кандидатов и т.д. Это важный этап создания деловой атмосферы в коллективе и поддержания имиджа Компании.</p>
                                <p class="education-paragraph-text">Основные требования к внешнему виду: сотрудник должен опрятно выглядеть, быть чистым и ухоженным. Запрещается приходить в спортивной одежде, потому что спортивная одежда не является офисной!</p>
                                <p class="education-paragraph-text">Для руководителей и менеджеров Компании рекомендуется деловой стиль одежды:<br>
                                <b>ПОЛУФОРМАЛЬНЫЙ.</b>
                                </p>
                            </div>
                            <p class="education-paragraph-text">
                            <b>Мужчины:</b> 
                            <ul>
                                <li class="education-list-item">брючный костюм с пиджаком;</li>
                                <li class="education-list-item">джинсы;</li>
                                <li class="education-list-item">рубашка, (необязательно галстук), поло;</li>
                                <li class="education-list-item">кожаные туфли, джинсы поло, полуботинки;</li> 
                                <li class="education-list-item">иной вид изделий, соответствующий деловому и опрятному виду.</li>
                            </ul>
                            <p class="education-paragraph-text">    
                            <b>Женщины:</b>
                            <ul> 
                                <li class="education-list-item">юбка или платье средней длины(не выше чем на ладонь от колена);</li>
                                <li class="education-list-item">брюки;</li>
                                <li class="education-list-item">блузки;</li>
                                <li class="education-list-item">закрытые туфли на каблуке/без каблука;</li> 
                                <li class="education-list-item">джинсы;</li> 
                                <li class="education-list-item">иной вид изделий, соответствующий деловому и опрятному виду.</li> 
                            </ul>
                            </p>
                            <p class="education-paragraph-text"> 
                            *Главное в деловом стиле – умеренность в деталях, гармоничное сочетание аксессуаров, одежды и обуви. Сотрудник обязан опрятно выглядеть, быть чистым и ухоженным.                            
                            </p>
                            <p class="education-paragraph-text">
                            <b>Не допускается/Ошибка:</b> 
                            <ul>
                                <li class="education-list-item">Ношение спортивной, пляжной, домашней одежды и обуви (шорты, майки, топики, леггинсы, сланцы, кроссовки и т.п.);</li> 
                                <li class="education-list-item">Одежда из прозрачных тканей, с голым пупком;</li> 
                                <li class="education-list-item">Приходить в состоянии похмелья, с неприятным, резким запахом, с немытой головой, в мятой одежде с пятнами, грязная обувь, не причесанными, с отросшей неухоженной стрижкой(мужчины).</li>
                            </ul>
                            </p>
                            <p class="education-paragraph-text">
                            Внешний вид сотрудников должен соответствовать требованиям настоящего Кодекса. Руководитель ориентирует команду собственным примером на соблюдение установленных Кодексом требований.
                            </p>
                            <div class="education-color-background"> 
                                <h3 class="text-center">📋Регламент рабочего дня</h3>
                            </div>
                            <p class="education-paragraph-text"><b>ПОДГОТОВКА К РАБОТЕ И ПЛАНИРОВАНИЕ</b></p>
                            <ul>
                                <li class="education-list-item">Приход на работу: Я приезжаю к 9:20 для подготовки рабочего места и проверки технических средств. В 9:30 я готов к работе. Это значит, что все находятся в боевом режиме и готовы к планерке / работе.<br>
                                <span class="education-text-marker">09.30 - это время, когда сотрудник ГОТОВ к работе.</span> Не пришел в офис и его нога вступила за порог, а когда он ГОТОВ и настроен на работу❤️</li>
                                <li class="education-list-item">Настрой: Важно настроиться на работу, исключив посторонние мысли и проблемы, чтобы эффективно использовать свои ресурсы.</li>
                                <li class="education-list-item"><span class="education-text-marker">Планирование: Минимальная цель на день — 11 лидов, оптимально — 15.</span> Первые 30 минут использую как фору, чтобы опережать план.</li> 
                                <li class="education-list-item"><span class="education-text-marker">Прозвон: Рекомендуется скорость 40-50 контактов в час.</span> Важно отрабатывать весь трафик, так как конверсия не зависит от времени обращения.</li> 
                                <li class="education-list-item">Обратная связь: После прозвона необходимо сообщить руководителю о результатах, чтобы он мог проанализировать конверсию и предпринять меры для её улучшения.</li>
                            </ul>
                            <p class="education-paragraph-text"><b>РАБОТА СО СКРИПТОМ</b></p>
                            <p class="education-paragraph-text">Ваша работа со скриптом и трафиком заключается в эффективной коммуникации и правильной интерпретации скриптов для достижения результатов. Основные моменты:</p>
                            <ul>
                                <li class="education-list-item">Скрипт разработан годами и служит основным инструментом, минимизирующим отказы.</li> 
                                <li class="education-list-item">Важно сохранять деловой стиль общения и поддерживать компетентную атмосферу.</li> 
                                <li class="education-list-item">Структура скрипта: приветствие, выяснение критериев, консультация, перевод на специалиста.</li> 
                                <li class="education-list-item">Будьте внимательны к клиентским вопросам для корректного изложения информации.</li>
                            </ul> 
                            <p class="education-paragraph-text">Работа с трафиком:</p> 
                            <ul>
                                <li class="education-list-item">Скорость прозвона должна быть оптимизирована (40-50 контактов в час).</li> 
                                <li class="education-list-item">Все трафики, независимо от давности обращения, требуют высокого уровня обработки.</li> 
                                <li class="education-list-item">Взаимодействие с клиентами на основе полученной информации, даже если кажется, что она уже известна.</li> 
                                <li class="education-list-item">Обратная связь с руководителем по низким конверсиям важна для анализа ситуации и принятия решений.
                                Каждый момент имеет значение, поэтому подходите к каждому взаимодействию с вниманием.</li>
                            </ul>
                            <div class="education-color-background"> 
                                <p class="education-paragraph-text"><b>🙈ЧТО МНЕ ДЕЛАТЬ, ЕСЛИ НЕ ПОЛУЧАЕТСЯ ЗАКРЫТЬ ПЛАН?</b></p>
                                <p class="education-paragraph-text">
                                Если у вас не получается, проанализируйте свою работу.<br>
                                «Какую ошибку я совершил?»<br>
                                Обратитесь к своему руководителю, что бы разобрать вместе.<br>
                                Пришлите свои записи руководителю(3-5 записей) и индивидуально разберите с ним.
                                </p>
                            </div>
                            <br>
                            <p class="education-paragraph-text text-center"><b>ВАЖНО! ЕСЛИ ВЫ КАК РАБОТНИК КОМПАНИИ ООО «МКГ» НЕ МОЖЕТЕ ИЗБРАТЬ ДЛЯ СЕБЯ ПРАВИЛЬНУЮ ПРОФЕССИОНАЛЬНУЮ ПОЗИЦИЮ В КОЛЛЕКТИВЕ, НЕ ЗНАЕТЕ, КАК ВЕСТИ СЕБЯ В ТОЙ ИЛИ ИНОЙ СИТУАЦИИ, СВЯЗАННОЙ С ВЫПОЛНЕНИЕМ ОБЯЗАННОСТЕЙ, — ❤️НЕ СТЕСНЯЙТЕСЬ И ОБРАТИТЕСЬ К СВОЕМУ НЕПОСРЕДСТВЕННОМУ РУКОВОДИТЕЛЮ И/ИЛИ В ОТДЕЛ ПО РАБОТЕ С ПЕРСОНАЛОМ</b></p>
                            <p class="education-paragraph-text text-center"><b>ЕСТЬ ВОПРОС? ПИШИТЕ:<br>
                                TELEGRAM: <a href="https://t.me/HR_MKG" target="_blank">HR_MKG</a>,<br>
                                ВОТСАПП: <a href="https://wa.me/79050303428" target="_blank">8(905)030-34-28</a></b></p>

                    </div><!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- container -->

        </div> <!-- content -->

    </div>


    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->


</div>
<!-- END wrapper -->



<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php'); ?>

<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="/scripts/rating.js"></script>