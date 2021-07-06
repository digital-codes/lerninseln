// https://ionicframework.com/blog/managing-state-in-vue-with-vuex/

// https://github.com/mhartington/vuex-todo

import { InjectionKey } from 'vue';
import { createStore, useStore as baseUseStore, Store, MutationTree, } from 'vuex';

// interfaces for our State and todos
export type Selection = { eventId: number; providerId: number};
export type Purchase = { ticketId: number; email: string; count: number };
export type Qrcode = {
   "event": string;"date": string; "time": string; 
    "count": number;"location": string;"qrsrc": string;
  };
export type State = { selection: Selection; purchase: Purchase; qrcode: Qrcode[] };


export const key: InjectionKey<Store <State> > = Symbol();
const state: State = {
  selection: {eventId: 0, providerId: 0},
  purchase: {ticketId: 0, email: "", count: 0},
  qrcode: []
};

/*
 * Mutations
 * How we mutate our state.
 * Mutations must be synchronous
 */
export const enum MUTATIONS {
  RESET_EVENT = 'RESET_EVENT',
  SET_EVENT = 'SET_EVENT',
  RESET_PURCHASE = 'RESET_PURCHASE',
  SET_PURCHASE = 'SET_PURCHASE',
  RESET_QR = 'RESET_QR',
  ADD_QR = 'ADD_QR',
}

const mutations: MutationTree<State> = {
  //[MUTATIONS.SET_EVENT](state, event: number, provider: number) {
    [MUTATIONS.SET_EVENT](state, selection: Selection ) {
      state.selection.eventId = selection.eventId;
      state.selection.providerId = selection.providerId;
    },
    [MUTATIONS.RESET_EVENT](state) {
      state.selection.eventId = 0;
      state.selection.providerId = 0;
    },
    [MUTATIONS.SET_PURCHASE](state, purchase: Purchase ) {
      state.purchase = purchase
    },
    [MUTATIONS.RESET_PURCHASE](state) {
      state.purchase.ticketId = 0;
      state.purchase.email = "";
      state.purchase.count = 0;
    },
    [MUTATIONS.RESET_QR](state) {
      state.qrcode = []
    },
    [MUTATIONS.ADD_QR](state, qr: Qrcode) {
      state.qrcode.push(qr)
    },
  };

/*
 * Actions
 * Perform async tasks, then mutate state
 */
/*
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
*/

export const store = createStore<State>({ state, mutations });

// our own useStore function for types
export function useStore() {
  return baseUseStore(key);
}
