// 请求配置
	// 请求域名 格式： https://您的域名
    // export  const	HTTP_REQUEST_URL= `https://api.pay.ttcni.top/`;
    export  const HTTP_REQUEST_URL= window.location.protocol + "//" + window.location.host;
	

	// 后台版本号
	export  const	SYSTEM_VERSION= 100

	// 以下配置在不做二开的前提下,不需要做任何的修改
	export  const	HEADER= {
		'content-type': 'application/json'
		
	}
	// 回话密钥名称 请勿修改此配置
	export  const	TOKENNAME= 'Authori-zation'
	// 缓存时间 0 永久
	export  const	EXPIRE= 0
	//分页最多显示条数
	export  const	LIMIT=10
	// 请求超时限制 默认10秒
	export  const	TIMEOUT= 60000