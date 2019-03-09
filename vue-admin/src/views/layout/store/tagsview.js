/**
 * +----------------------------------------------------------------------
 * | InitAdmin/vue-admin [ InitAdmin渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2019 http://initadmin.net All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: jry <ijry@qq.com>
 * +----------------------------------------------------------------------
*/

import util from './util.js'
export default {
    state: {
        visitedviews:[],//存放所有浏览过的且不重复的路由数据
     },
     getters: {
        get_visitedviews: state => {
            return state.visitedviews
        }
     },
     mutations: {
        add_visited_views:(state, data)=>{
            //打开新页签--添加路由数据的方法
            if(state.visitedviews.some(v=>v.path==data.path))return;
            state.visitedviews.push({
                name:data.name,
                path:data.path,
                title:data.meta.title || '无标题'
            })
            util.setTagviewsInLocalstorage([...state.visitedviews])
        },
        del_visited_views:(state, data)=>{
            //关闭页签--删除路由数据的方法
            for(let [i,v] of state.visitedviews.entries()){
                if(v.path == data.path){
                    state.visitedviews.splice(i,1)
                    break
                }
            }
            util.setTagviewsInLocalstorage([...state.visitedviews])
        },
        set_visited_views:(state, data)=>{
            //直接设置打开的标签
            if (data) {
                state.visitedviews = data
                util.setTagviewsInLocalstorage([...state.visitedviews])
            } else {
                state.visitedviews = util.getTagviewsFromLocalstorage()
            }
        },
     },
     actions: { 
        //调用这里去触发mutations，如何调用？在组件内使用this.$store.dispatch('action中对应名字', 参数)
        addVisitedViews({commit}, data){
            //通过解构赋值得到commit方法
            commit('add_visited_views', data)//去触发ADD_VISITED_VIEWS，并传入参数
        },
        delVisitedViews({commit, state}, data){
            //删除数组存放的路由之后，需要再去刷新路由，这是一个异步的过程，需要有回掉函数，
            //所以使用并返回promise对象，也可以让组件在调用的时候接着使用.then的方法
            return new Promise((resolve)=>{
                //resolve方法：未来成功后回调的方法
                commit('del_visited_views', data);
                resolve([...state.visitedviews]);
            })
        },
        setVisitedViews({commit}, data){
            //通过解构赋值得到commit方法
            commit('set_visited_views', data)
        }
     }
}
