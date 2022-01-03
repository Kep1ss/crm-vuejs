/* NOTE */
/* refresh token does working with two ajax when running in same time */
/* if type response.data is BLOB it will pass away to refresh token directly */

export default function ({app,$axios}) {
    $axios.onRequest(config => {
        config.headers["isNotAccessOtherLogin"] = app.$cookiz.get("isNotAccessOtherLogin") || "";
    })
  

    $axios.onError(err => {
        if(!err.response){
            throw err;
        }

        if(err.response.status !== 401){
            throw err;
        }

        if(!["Token is expired"].includes(err.response.data.error)){
            if(!(err.response.data instanceof Blob)){
                if(!["Token is blacklist"].includes(err.response.data.error)){
                    app.$auth.reset();
                    throw err;
                }

                err.config.headers["Try"] = ((err.config.headers["Try"] || 0) + 1);

                if(err.config.headers["Try"] >= 4){
                    app.$auth.reset();
                    throw err;
                }

                err.config.headers["Authorization"] = app.$cookiz.get('auth._token.local');

                return $axios(err.config);
            }else{
                return $axios.post("/refresh")
                .then(res => {
                    return app.$auth.setUserToken(res.data.access_token)
                    .then(() => {
                        err.config.headers['Authorization'] = "Bearer "+res.data.access_token;
                        return $axios(err.config)
                    });
                });

            }
        }else{
            return $axios.post("/refresh")
            .then(res => {
                return app.$auth.setUserToken(res.data.access_token)
                .then(() => {
                    err.config.headers['Authorization'] = "Bearer "+res.data.access_token;
                    return $axios(err.config)
                });
            });
        }
    });
}
