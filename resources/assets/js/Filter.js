import moment from "moment";
import Vue from 'vue'

Vue.filter('DateFormat',(date)=>{
  return moment(date).format("MMM Do YY")
});

Vue.filter('short_text',(text,length,suffix)=>{
  return text.substring(0,length)+suffix;
});

Vue.filter('NumberFormat',(number)=>{ 
  return number.toFixed(2); 
});


Vue.filter('MenuFormat',(text)=>{ 
  console.log(text)
});

Vue.filter('capitalize', function (value) {
  if (!value) return ''
  value = value.toString()
  var text =  value.toLowerCase().replace(/\s+/, '-')
  return value.charAt(0).toUpperCase() + text.slice(1) 
})


Vue.filter('CheckAuth',(number)=>{  
  if(localStorage.getItem('access_token') != 'null'){
    return true;
  }else{
    return false;
  } 

});
 