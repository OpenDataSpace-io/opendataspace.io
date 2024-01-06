import { useTranslation } from 'next-i18next';
const SelectForm = (props:any) => {
  const { selectedForm, formList, handleFormSelect } = props;
  const { t } = useTranslation('common');

  return (
    <>
        <span>{ t('things.form.selectform')} 
            <select value={selectedForm} onChange={handleFormSelect}>
                {formList.map((form: { id: string, name: string }) => (
                    <option key={form.id} value={form.id}>{form.name}</option>
                ))}
            </select>
        </span>
    </>
  );
};

export default SelectForm;

