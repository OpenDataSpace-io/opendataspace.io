import { Menu as ReactAdminMenu } from "react-admin";
import DataObjectIcon from '@mui/icons-material/DataObject';

const Menu = () => (
  <ReactAdminMenu>
    <ReactAdminMenu.Item to="/admin/things" primaryText="Things" leftIcon={<DataObjectIcon/>}/>
  </ReactAdminMenu>
);
export default Menu;
