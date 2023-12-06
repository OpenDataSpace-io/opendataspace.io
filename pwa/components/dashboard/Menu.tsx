import { Menu as ReactAdminMenu } from "react-admin";
import MenuBookIcon from "@mui/icons-material/MenuBook";
import CommentIcon from "@mui/icons-material/Comment";
import DataObjectIcon from '@mui/icons-material/DataObject';

const Menu = () => (
  <ReactAdminMenu>
    <ReactAdminMenu.Item to="/dashboard/things" primaryText="Things" leftIcon={<DataObjectIcon/>}/>
  </ReactAdminMenu>
);
export default Menu;
