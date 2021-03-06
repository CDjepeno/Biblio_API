import axios from 'axios';
import React from 'react';
import { EDITOR_API } from '../config';

export default class EditorServices 
{
    static findAll() {
        return axios
            .get(EDITOR_API)
            .then(response => response.data['hydra:member'])
            .catch(error => this.handleError(error))
    }

    static handleError(error) {
        console.error(error);
    }
}