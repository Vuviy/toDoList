<!doctype html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.7.1.js"
            integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <title>To‑Do List</title>
    <style>
        :root{
            --bg:#0f1724; --card:#0b1220; --accent:#6ee7b7; --muted:#9aa4b2;
            --danger:#ff6b6b; --warning:#ffb86b; --glass: rgba(255,255,255,0.03);
            --radius:14px; --glass-2: rgba(255,255,255,0.02);
        }
        *{box-sizing:border-box;font-family:Inter,Segoe UI,Roboto,Arial,sans-serif}
        html,body{height:100%;margin:0;background:linear-gradient(180deg,#061021 0%, #07122a 100%);color:#e6eef6}
        .wrap{min-height:100%;display:grid;place-items:center;padding:32px}
        .card{width:100%;max-width:900px;background:linear-gradient(180deg,rgba(255,255,255,0.1),transparent);border-radius:var(--radius);padding:28px;box-shadow:0 8px 30px rgba(2,6,23,0.6);backdrop-filter: blur(6px);}
        h1{margin:0 0 12px;font-size:20px;display:flex;gap:12px;align-items:center}
        .subtitle{color:var(--muted);font-size:13px;margin-bottom:18px}
        form{display:grid;grid-template-columns:repeat(12,1fr);gap:10px;align-items:end}
        label{font-size:13px;color:var(--muted)}
        input[type="text"],select,input[type="date"]{padding:10px 12px;border-radius:10px;border:1px solid var(--glass);background:transparent;color:inherit;outline:none}
        .col-6{grid-column:span 6}
        .col-4{grid-column:span 4}
        .col-3{grid-column:span 3}
        .col-12{grid-column:span 12}
        button{padding:10px 14px;border-radius:10px;border:0;background:var(--accent);color:#032;cursor:pointer;font-weight:600}
        .tasks{margin-top:20px}
        .task{display:flex;gap:12px;align-items:center;padding:12px;border-radius:12px;background:var(--glass-2);border:1px solid rgba(255,255,255,0.02)}
        .task + .task{margin-top:10px}
        .task .info{flex:1}
        .task .title{font-weight:700}
        .task .meta{font-size:13px;color:var(--muted);margin-top:6px}
        .badge{padding:6px 8px;border-radius:999px;font-weight:700;font-size:12px}
        .cat-1{background:rgba(102,126,234,0.12);color:#9fb0ff}
        .cat-2{background:rgba(110,231,183,0.08);color:var(--accent)}
        .cat-3{background:rgba(255,182,107,0.06);color:var(--warning)}
        .prio-low{background:rgba(255,255,255,0.03);color:var(--muted)}
        .prio-med{background:rgba(255,255,255,0.04);color:var(--warning)}
        .prio-high{background:rgba(255,0,80,0.08);color:var(--danger)}
        .controls{display:flex;gap:8px}
        .icon-btn{border:1px solid rgba(255,255,255,0.3);padding:8px;border-radius:10px;cursor:pointer}
        .center-small{display:flex;gap:8px;align-items:center}
        option {
            background: #0b1220;
            color: #e6eef6;
        }
        .empty{color:var(--muted);padding:18px;text-align:center}
        @media(max-width:720px){
            form{grid-template-columns:repeat(6,1fr)}
            .col-6{grid-column:span 6}
            .col-4{grid-column:span 3}
            .col-3{grid-column:span 3}
        }
    </style>
</head>
<body>


<?php
//var_dump($tasks);
//die();
//
//?>
<div class="wrap">
    <div class="card">
        <h1>📝 Мій To‑Do список</h1>
        <div class="subtitle">Додай завдання з назвою, категорією, пріоритетом та терміном виконання. Все зберігається локально.</div>

        <form id="taskForm" autocomplete="off">
            <div class="col-6">
                <label for="title">Назва</label>
                <input id="title" name="title" type="text" placeholder="Наприклад: Зробити код-рев'ю" required>
            </div>

            <div class="col-3">
                <label for="category">Категорія</label>
                <select id="category" name="category">
                    <option value="1">Робота</option>
                    <option value="2">Особисте</option>
                    <option value="3">Навчання</option>
                </select>
            </div>

            <div class="col-3">
                <label for="priority">Пріоритет</label>
                <select id="priority" name="priority">
                    <option value="1">Низький</option>
                    <option value="2" selected>Середній</option>
                    <option value="3">Високий</option>
                </select>
            </div>

            <div class="col-4">
                <label for="due">Термін виконання</label>
                <input name="date" id="due" type="date">
<!--                <input name="id" type="text" value="68e284bb65347">-->
            </div>

            <div class="col-4">
                <label>&nbsp;</label>
                <button id="addBtn" type="submit">Додати завдання</button>
            </div>

            <div class="col-4 center-small">
                <label>&nbsp;</label>
                <div style="display:flex;gap:8px">
                    <button id="clearCompleted" type="button" class="icon-btn" title="Прибрати виконані">Очистити виконані</button>
                    <button id="clearAll" type="button" class="icon-btn" title="Видалити всі">Видалити всі</button>
                </div>
            </div>
        </form>

        <div class="tasks" id="tasksList">

            <?php
            if(count($tasks) > 0){

                foreach($tasks as $task){

//                    var_dump($task->getFields()['id']);
//                    die();

                    $categories = [
                        1 => 'Робота',
                        2 => 'Навчання',
                        3 => 'Особисте',
                    ];

                    $catName = $categories[$task->getFields()['category']] ?? 'Невідомо';

                    $priorities = [
                        1 => 'Потім зробиш',
                        2 => 'Нормально',
                        3 => 'Терміново',
                    ];

                    $prioritiesClass = [
                        1 => 'low',
                        2 => 'medium',
                        3 => 'high',
                    ];

                    $priorityName = $priorities[$task->getFields()['priority']] ?? 'Невідомо';
                    $priorityClass = $prioritiesClass[$task->getFields()['priority']] ?? 'high';


                    ?>
                    <div class="task" data-id="<?php echo htmlspecialchars($task->getFields()['id'])  ?>">
                        <div class="info">
                            <div class="title"><?php echo htmlspecialchars($task->getFields()['title'])  ?></div>
                            <div class="meta">
                                <span class="badge cat-<?php echo htmlspecialchars($task->getFields()['category']) ?>"><?php echo htmlspecialchars($catName) ?></span>
                                &nbsp;
                                <span class="badge prio-<?php echo htmlspecialchars($priorityClass) ?>"><?php echo htmlspecialchars($priorityName)  ?></span>
                                &nbsp; • &nbsp; <strong>Термін:</strong> <?php echo htmlspecialchars($task->getFields()['date']) ?>
                            </div>
                        </div>
                        <div class="controls">
                            <button class="icon-btn">Готово</button>
                            <button class="icon-btn">Редаг.</button>
                            <button class="icon-btn">Видал.</button>
                        </div>
                    </div>


            <?php
                }
            } else{
                ?>
                <div class="empty">Список завдань порожній — додай перше завдання вище.</div>
            <?php
            }
            ?>
<!--            <div class="empty">Список завдань порожній — додай перше завдання вище.</div>-->
<!---->
<!--            <div class="task">-->
<!--                <div class="info">-->
<!--                    <div class="title">Зробити код‑рев'ю</div>-->
<!--                    <div class="meta">-->
<!--                        <span class="badge cat-1">Робота</span>-->
<!--                        &nbsp;-->
<!--                        <span class="badge prio-high">Високий</span>-->
<!--                        &nbsp; • &nbsp; <strong>Термін:</strong> 2025-10-10-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="controls">-->
<!--                    <button class="icon-btn">Готово</button>-->
<!--                    <button class="icon-btn">Редаг.</button>-->
<!--                    <button class="icon-btn">Видал.</button>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!---->
<!--            <div class="task">-->
<!--                <div class="info">-->
<!--                    <div class="title">Купити продукти</div>-->
<!--                    <div class="meta">-->
<!--                        <span class="badge cat-2">Особисте</span>-->
<!--                        &nbsp;-->
<!--                        <span class="badge prio-medium">Середній</span>-->
<!--                        &nbsp; • &nbsp; <strong>Термін:</strong> 2025-10-05-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="controls">-->
<!--                    <button class="icon-btn">Готово</button>-->
<!--                    <button class="icon-btn">Редаг.</button>-->
<!--                    <button class="icon-btn">Видал.</button>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!---->
<!--            <div class="task">-->
<!--                <div class="info">-->
<!--                    <div class="title">Прочитати 20 сторінок книги</div>-->
<!--                    <div class="meta">-->
<!--                        <span class="badge cat-3">Навчання</span>-->
<!--                        &nbsp;-->
<!--                        <span class="badge prio-low">Низький</span>-->
<!--                        &nbsp; • &nbsp; <strong>Термін:</strong> 2025-10-20-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="controls">-->
<!--                    <button class="icon-btn">Готово</button>-->
<!--                    <button class="icon-btn">Редаг.</button>-->
<!--                    <button class="icon-btn">Видал.</button>-->
<!--                </div>-->
<!--            </div>-->
        </div>

    </div>
</div>

<script>

    $(function () {
        $('#taskForm').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: '/form/submit',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (resp) {
                    // $('#result').show()
                    // $('#res').text('');
                    //
                    // $('#res').text(resp.password);
                    // $('#entropy').text('Entrorpy:  ' + resp.entropy);
                },
                error: function (xhr) {

                    $('#result').text('Error: ' + xhr.status);
                }
            });
        });
    });

</script>
</body>
</html>
