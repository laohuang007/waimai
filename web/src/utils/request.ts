import axios from "axios";
import {
	HTTP_REQUEST_URL,
	HEADER,
	TOKENNAME,
	TIMEOUT
} from '@/config/index';
import { showMessage } from "./status"; // 引入状态码文件
// import { ElMessage } from "element-plus"; // 引入el 提示框，这个项目里用什么组件库这里引什么
import { showDialog  } from 'vant';

// 设置接口超时时间
axios.defaults.timeout = TIMEOUT;
axios.defaults.baseURL =HTTP_REQUEST_URL + "/api" ;  // 自定义接口地址

const token = () => {
  if (sessionStorage.getItem("token")) {
    return sessionStorage.getItem("token");
  } else {
    return sessionStorage.getItem("token");
  }
};

//请求拦截
axios.interceptors.request.use(
  (config) => {
    // 配置请求头
    config.headers["Content-Type"] = "application/json;charset=UTF-8";
    config.headers["token"] = token();
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// 响应拦截
axios.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    const { response } = error;
    if (response) {
      // 请求已发出，但是不在2xx的范围
      let msg=showMessage(response.status); // 传入响应码，匹配响应码对应信息
      if(msg){
        showDialog({ confirmButtonText:"OK",message: msg });
      }
      return Promise.reject(response.data);
    } else {
    //   ElMessage.warning("网络连接异常,请稍后再试!");
      showDialog({ confirmButtonText:"OK",message: 'Network connection abnormality' });
    }
  }
);

// 封装 请求并导出
export function request(data: any) {
  return new Promise((resolve, reject) => {
    const promise = axios(data);
    //处理返回
    promise
      .then((response: any) => {
        let res=response.data;
        // console.log(res)
        if(res.code==1){
            // showDialog({ message: 'res.msg'});
            return resolve(res.data);
        }else{
          showDialog({ confirmButtonText:"OK",message: res.msg});
          return;
        }
        resolve(res.data);
        
      })
      .catch((err: any) => {
        reject(err.data);
      });
  });
}