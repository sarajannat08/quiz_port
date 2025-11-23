<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Create Question Paper</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="http://localhost/quiz_port/assets/css/style.css?v=<?php echo time(); ?>">

<link rel="icon" type="image/png" href="/quiz_port/assets/images/pic1.png">


<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(to right, #2f679eff, #d9e2ec);
    margin: 0;
    padding: 0;
}
.container {
    max-width: 900px;
    margin: 40px auto;
    padding: 20px;
}
.card {
    background: #5a2e2eff;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}
.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}
input, textarea {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
    transition: border-color 0.3s, box-shadow 0.3s;
}
input:focus, textarea:focus {
    border-color: #021846ff;
    box-shadow: 0 0 5px rgba(243, 155, 228, 0.5);
    outline: none;
}
button, a.btn {
    padding: 10px 20px;
    margin-top: 10px;
    border: none;
    border-radius: 6px;
    background: #e8c1ffff;
    color: #380404ff;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background 0.3s, transform 0.2s;
}
button:hover, a.btn:hover {
    background: #43a047;
    transform: scale(1.05);
}
#editorArea, #previewArea {display: none; opacity:0; transition: opacity 0.5s ease;}
.questionBox {
    border-bottom: 1px solid #ddd;
    margin-bottom: 15px;
    padding-bottom: 10px;
    transition: background 0.3s;
}
.questionBox:hover { background: #f1f8e9; }
.radio-group input { margin-right: 5px; }
h3 { color: #4caf50; }
</style>
</head>

<body>
<div class="container">
<h2 class="mb-3 animate-fade-in delay-1" style="color: #160436ff;">💻 Create Question Paper</h2>
<div class="card" id="metaCard">
    <label>Class:</label>
    <input id="meta_class" placeholder="Enter class">
    <label>Subject:</label>
    <input id="meta_subject" placeholder="Enter subject">
    <label>Institution:</label>
    <input id="meta_inst" placeholder="Enter institution">
    <label>Total Questions:</label>
    <input type="number" id="meta_total" placeholder="Number of questions">

    <button onclick="startMaking()">Start</button>
</div>

<div id="editorArea" class="card">
    <h3 id="qNumber"></h3>

    <textarea id="qText" placeholder="Write question text..."></textarea>

    <input id="opt1" placeholder="Option 1">
    <input id="opt2" placeholder="Option 2">
    <input id="opt3" placeholder="Option 3">
    <input id="opt4" placeholder="Option 4">

    <label>Correct Option:</label>
    <div class="radio-group">
        <input type="radio" name="correct" value="1">1
        <input type="radio" name="correct" value="2">2
        <input type="radio" name="correct" value="3">3
        <input type="radio" name="correct" value="4">4
    </div>

    <button onclick="addQuestion()">Add Question</button>
    <button onclick="finishPaper()">Finish</button>
</div>

<div id="previewArea" class="card">
    <h3>Preview</h3>
    <div id="previewMeta"></div>
    <div id="previewList"></div>

    <div class="mt-3 d-flex gap-2">
      <button onclick="savePaper()">📦 Save to Database</button>
      <a href="history.php" class="btn btn-secondary">📜 My History</a>
    </div>
</div>
</div>

<script>
let total = 0;
let index = 1;
let questions = [];
let meta = {};

function startMaking() {
    total = parseInt(document.getElementById('meta_total').value);
    if(!total){ alert("Enter total questions"); return; }
    index = 1;
    document.getElementById('editorArea').style.display = "block";
    setTimeout(()=>{document.getElementById('editorArea').style.opacity=1;},10);
    document.getElementById('qNumber').textContent = "Question " + index + " of " + total;
    document.getElementById('metaCard').style.opacity = 0;
    setTimeout(()=>{document.getElementById('metaCard').style.display="none";},300);
}

function addQuestion() {
    let qText = document.getElementById('qText').value.trim();
    let a = document.getElementById('opt1').value.trim();
    let b = document.getElementById('opt2').value.trim();
    let c = document.getElementById('opt3').value.trim();
    let d = document.getElementById('opt4').value.trim();
    let correct = document.querySelector('input[name="correct"]:checked');

    if(!qText || !a || !b || !c || !d || !correct){
        alert("Fill everything");
        return;
    }

    questions.push({
      question: qText,
      option1: a,
      option2: b,
      option3: c,
      option4: d,
      correct: parseInt(correct.value, 10)
    });

    // clear inputs
    document.getElementById('qText').value = "";
    document.getElementById('opt1').value = "";
    document.getElementById('opt2').value = "";
    document.getElementById('opt3').value = "";
    document.getElementById('opt4').value = "";
    document.querySelectorAll('input[name="correct"]').forEach(r=>r.checked=false);

    if (index < total) {
      index++;
      document.getElementById('qNumber').textContent = "Question " + index + " of " + total;
    } else {
      alert("You added all questions.");
    }
}

function finishPaper() {
    if(questions.length === 0){ alert("Add at least 1 question."); return; }
    document.getElementById('previewArea').style.display = "block";
    setTimeout(()=>{document.getElementById('previewArea').style.opacity=1;},10);

    meta = {
      class: document.getElementById('meta_class').value,
      subject: document.getElementById('meta_subject').value,
      institution: document.getElementById('meta_inst').value,
      total: total
    };

    document.getElementById('previewMeta').innerHTML =
        "<b>Class:</b> "+meta.class+" | "+
        "<b>Subject:</b> "+meta.subject+" | "+
        "<b>Institution:</b> "+meta.institution;

    let html = "";
    questions.forEach((q,i)=>{
        html += `<div class="questionBox">
                    <b>Q${i+1}:</b> ${q.question}
                    <ul>
                        <li>${q.option1}</li>
                        <li>${q.option2}</li>
                        <li>${q.option3}</li>
                        <li>${q.option4}</li>
                    </ul>
                </div>`;
    });
    document.getElementById('previewList').innerHTML = html;
}

async function savePaper() {
  const payload = { meta, questions };

  try {
    const res = await fetch('save_paper.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (data.success) {
      alert("✅ Paper saved! You can view it in History.");
      window.location.href = 'history.php'; // redirect after save
    } else {
      alert("❌ Save failed: " + data.message);
    }
  } catch (e) {
    alert("Error: " + e.message);
  }
}

function printWithStudent() {
  window.print();
}
</script>
</body>
</html>
