const skillContainner = document.getElementById("skillContainner");
const skills = document.getElementById("skills");

function addMoreSkill() {
  if (skillContainner.children.length < 5) {
    const element = document.createElement("div");
    element.classList.add("skill");
    element.innerHTML = `
        <input type="text" class="input qualification" placeholder="I work at Company..">
        <button type="button" class=" btn delete" onclick="deleteSkill(this)">❌</button>
    `;
    skillContainner.appendChild(element);
  } else {
    alert("You can only have 5 Qualifications at max!");
  }
}

function deleteSkill(element) {
  element.parentElement.remove();
}

function handleProfileSubmit() {
  const compiledSkills = [];
  const qualification = document.querySelectorAll(".qualification");
  qualification.forEach((skill) => {
    if (skill.value.trim()) {
      compiledSkills.push(skill.value);
    }
  });
  const skillString = JSON.stringify(compiledSkills);
  skills.value = skillString;

  return true;
}
