import simple from './simple';
import { Sample } from './Sample';
import deepFreeze from 'deep-freeze-es6';

const _samples: Record<string, Sample> = {
  Blank: { schema: {}, uiSchema: {}, formData: {} },
  Simple: simple,
};

export const samples = deepFreeze(_samples);