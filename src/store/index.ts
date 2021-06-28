// https://ionicframework.com/blog/managing-state-in-vue-with-vuex/

// https://github.com/mhartington/vuex-todo

import { InjectionKey } from 'vue';
import { createStore, useStore as baseUseStore, Store, MutationTree, ActionTree, } from 'vuex';

// interfaces for our State and todos
export type Todo = { id: number; title: string; note?: string };
export type Selection = { eventId: number; providerId: number };
export type State = { todos: Todo[]; selection: Selection };

export const key: InjectionKey<Store<State>> = Symbol();
const state: State = {
  todos: [
    {
      title: 'Learn Vue',
      note: 'https://v3.vuejs.org/guide/introduction.html',
      id: 0,
    },
    {
      title: 'Learn TypeScript',
      note: 'https://www.typescriptlang.org',
      id: 1,
    },
    { title: 'Learn Vuex', note: 'https://next.vuex.vuejs.org', id: 2 },
  ],
  selection: {eventId: 0, providerId: 0},
};

/*
 * Mutations
 * How we mutate our state.
 * Mutations must be synchronous
 */
export const enum MUTATIONS {
  ADD_TODO =  'ADD_TODO',
  DEL_TODO =  'DEL_TODO',
  EDIT_TODO = 'EDIT_TODO',
  RESET_EVENT = 'RESET_EVENT',
  SET_EVENT = 'SET_EVENT'
}

const mutations: MutationTree<State> = {
  [MUTATIONS.ADD_TODO](state, newTodo: Todo) {
    state.todos.push({ ...newTodo });
  },
  [MUTATIONS.DEL_TODO](state, todo: Todo) {
    state.todos.splice(state.todos.indexOf(todo), 1);
  },
  [MUTATIONS.EDIT_TODO](state, todo: Todo) {
    const ogIndex = state.todos.findIndex(t => t.id === todo.id)
    state.todos[ogIndex] = todo;
  },
  //[MUTATIONS.SET_EVENT](state, event: number, provider: number) {
  [MUTATIONS.SET_EVENT](state, selection: Selection ) {
    state.selection.eventId = selection.eventId;
    state.selection.providerId = selection.providerId;
  },
  [MUTATIONS.RESET_EVENT](state) {
    state.selection.eventId = 0;
    state.selection.providerId = 0;
  },
};

/*
 * Actions
 * Perform async tasks, then mutate state
 */

export const enum ACTIONS { ADD_RND_TODO = 'ADD_RND_TODO', }
const actions: ActionTree<State, any> = {
  [ACTIONS.ADD_RND_TODO](store) {
        const newTodo: Todo = {
          title: "title",
          id: Math.random(),
          note: "madmakfmakfmkefm"
        }
        store.commit(MUTATIONS.ADD_TODO, newTodo);
  },
};

export const store = createStore<State>({ state, mutations, actions });

// our own useStore function for types
export function useStore() {
  return baseUseStore(key);
}

