const userList = document.getElementById('userlist');

document.addEventListener('DOMContentLoaded',()=>{
    fetch('../backend/fetch_users.php').then(response=>{
        if(!response.ok){
            throw new Error('Network response was not ok')
        }
        return response.json();
    }).then(users =>{
        users.forEach((user)=>{
            const li = document.createElement('li');
            li.textContent = user.name;
            userList.appendChild(li);
        })
    }).catch(error=> console.error("Error Fetching Users: ", error));
})